<?php
session_start();
require_once '../../database/db_config.php';
require_once '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

if (isset($_POST['import_products']) && isset($_FILES['import_file'])) {
    $file = $_FILES['import_file'];
    
    // Validate file type
    $allowed_extensions = ['xls', 'xlsx'];
    $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    
    if (!in_array($file_ext, $allowed_extensions)) {
        $_SESSION['error'] = "Invalid file format. Please upload an Excel file (.xls or .xlsx).";
        header("Location: add-product.php");
        exit();
    }

    try {
        $spreadsheet = IOFactory::load($file['tmp_name']);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        // Remove header row
        array_shift($rows);
        
        $success_count = 0;
        $error_count = 0;
        $errors = [];

        foreach ($rows as $index => $row) {
            // Skip empty rows
            if (empty(array_filter($row))) {
                continue;
            }

            // Map columns (adjust indices based on download_sample.php)
            $brand_name = trim($row[0] ?? '');
            $category_name = trim($row[1] ?? '');
            $sub_category_name = trim($row[2] ?? '');
            $title = trim($row[3] ?? '');
            $sku = trim($row[4] ?? '');
            $hsn_code = trim($row[5] ?? '');
            $description = trim($row[6] ?? '');
            $specifications = trim($row[7] ?? '');
            $mrp = (float)($row[8] ?? 0);
            $sales_price = (float)($row[9] ?? 0);
            $stock = (int)($row[10] ?? 0);
            $is_price_request = (int)($row[11] ?? 0);
            $meta_title = trim($row[12] ?? '');
            $meta_description = trim($row[13] ?? '');
            $meta_keywords = trim($row[14] ?? '');
            
            // Calculate discount
            $discount_percentage = 0;
            if ($mrp > 0 && $sales_price > 0 && $mrp > $sales_price) {
                $discount_percentage = (($mrp - $sales_price) / $mrp) * 100;
            }

            // 1. Get Brand ID
            $brand_id = 0;
            if ($brand_name) {
                $stmt = $conn->prepare("SELECT id FROM brands WHERE name = ?");
                $stmt->bind_param("s", $brand_name);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    $brand_id = $res->fetch_assoc()['id'];
                }
                $stmt->close();
            }

            // 2. Get Category ID (requires Brand ID)
            $category_id = 0;
            if ($category_name && $brand_id) {
                $stmt = $conn->prepare("SELECT id FROM product_categories WHERE name = ? AND brand_id = ?");
                $stmt->bind_param("si", $category_name, $brand_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    $category_id = $res->fetch_assoc()['id'];
                }
                $stmt->close();
            }

            // 3. Get Sub Category ID (requires Category ID)
            $sub_category_id = 0;
            if ($sub_category_name && $category_id) {
                $stmt = $conn->prepare("SELECT id FROM product_sub_categories WHERE name = ? AND category_id = ?");
                $stmt->bind_param("si", $sub_category_name, $category_id);
                $stmt->execute();
                $res = $stmt->get_result();
                if ($res->num_rows > 0) {
                    $sub_category_id = $res->fetch_assoc()['id'];
                }
                $stmt->close();
            }

            if (!$brand_id || !$category_id || empty($title)) {
                $error_count++;
                $errors[] = "Row " . ($index + 2) . ": Missing Brand, Category, or Title. (Brand: $brand_name, Cat: $category_name)";
                continue;
            }

            $sub_category_id = ($sub_category_id > 0) ? $sub_category_id : 'NULL';

            // Insert Product
            $sql = "INSERT INTO products (brand_id, category_id, sub_category_id, title, sku, hsn_code, description, specifications, mrp, sales_price, discount_percentage, stock, is_price_request, meta_title, meta_description, meta_keywords) 
                    VALUES (?, ?, $sub_category_id, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iisssssdddiisss", $brand_id, $category_id, $title, $sku, $hsn_code, $description, $specifications, $mrp, $sales_price, $discount_percentage, $stock, $is_price_request, $meta_title, $meta_description, $meta_keywords);
            
            if ($stmt->execute()) {
                $success_count++;
            } else {
                $error_count++;
                $errors[] = "Row " . ($index + 2) . ": Database Error - " . $conn->error;
            }
            $stmt->close();
        }

        if ($success_count > 0) {
            $_SESSION['success'] = "Successfully imported $success_count products.";
        }
        
        if ($error_count > 0) {
            $_SESSION['error'] = "Failed to import $error_count rows. <br>" . implode("<br>", array_slice($errors, 0, 5));
            if(count($errors) > 5) $_SESSION['error'] .= "<br>...and more.";
        }

    } catch (Exception $e) {
        $_SESSION['error'] = "Error processing file: " . $e->getMessage();
    }
} else {
    $_SESSION['error'] = "No file uploaded.";
}

header("Location: index.php");
exit();
?>
