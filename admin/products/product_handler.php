<?php
session_start();
require_once '../../database/db_config.php';
require_once '../../includes/url_helper.php';

// Auth Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Redirect Helper Functions
function getRedirectUrl($default = 'index.php') {
    $params = [];
    foreach ($_REQUEST as $key => $value) {
        if (strpos($key, 'redirect_') === 0 && !empty($value)) {
            $params[str_replace('redirect_', '', $key)] = $value;
        }
    }
    // For GET-based actions where params are in URL
    foreach (['page', 'brand_id', 'category_id', 'sub_category_id'] as $std_param) {
        if (!isset($params[$std_param]) && isset($_GET[$std_param])) {
            $params[$std_param] = $_GET[$std_param];
        }
    }
    
    if (empty($params)) return $default;
    return 'index.php?' . http_build_query($params);
}

// Function to handle image upload
function uploadImage($file, $folder) {
    // Correct path relative to where this script is located (admin/products/)
    // We want to upload to assets/uploads/{folder}/
    // So go up two levels to root, then into assets...
    $target_dir = "../../assets/uploads/" . $folder . "/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "assets/uploads/" . $folder . "/" . $new_filename;
        }
    }
    return false;
}

// Handle Add Product
if (isset($_POST['add_product'])) {
    $brand_id = (int)$_POST['brand_id'];
    $category_id = (int)$_POST['category_id'];
    $sub_category_id = !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : 'NULL';
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $specifications = $conn->real_escape_string($_POST['specifications']);
    $mrp = (float)$_POST['mrp'];
    $sales_price = (float)$_POST['sales_price'];
    $discount_percentage = (float)$_POST['discount_percentage'];
    $stock = (int)$_POST['stock'];
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']); 
    $is_price_request = isset($_POST['is_price_request']) ? 1 : 0;
    
    $moq = isset($_POST['moq']) ? (int)$_POST['moq'] : 1;
    $unit = isset($_POST['unit']) ? $conn->real_escape_string($_POST['unit']) : 'nos';
    
    // Slug handling
    $user_slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
    $base_slug = !empty($user_slug) ? createSlug($user_slug) : createSlug($_POST['title']);
    $slug = $base_slug;
    $counter = 1;
    while (true) {
        $chk_slug = $conn->real_escape_string($slug);
        $chk_res = $conn->query("SELECT id FROM products WHERE slug = '$chk_slug'");
        if ($chk_res && $chk_res->num_rows > 0) {
            $counter++;
            $slug = $base_slug . '-' . $counter;
        } else {
            break;
        }
    }
    $slug_escaped = $conn->real_escape_string($slug);

    // If price on request, set prices to 0
    if($is_price_request) {
        $mrp = 0;
        $sales_price = 0;
        $discount_percentage = 0;
    }
    
    $featured_image = null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $featured_image = uploadImage($_FILES['featured_image'], 'products');
    }

    $sku = $conn->real_escape_string($_POST['sku']);
    $hsn_code = $conn->real_escape_string($_POST['hsn_code']);

    $sql = "INSERT INTO products (brand_id, category_id, sub_category_id, title, slug, sku, hsn_code, description, specifications, mrp, sales_price, discount_percentage, stock, moq, unit, is_price_request, featured_image, meta_title, meta_description, meta_keywords) 
            VALUES ('$brand_id', '$category_id', $sub_category_id, '$title', '$slug_escaped', '$sku', '$hsn_code', '$description', '$specifications', '$mrp', '$sales_price', '$discount_percentage', '$stock', '$moq', '$unit', '$is_price_request', '$featured_image', '$meta_title', '$meta_description', '$meta_keywords')";

    if ($conn->query($sql) === TRUE) {
        $product_id = $conn->insert_id;
        
        // Handle Gallery Images
        if (isset($_FILES['gallery_images'])) {
            $total_files = count($_FILES['gallery_images']['name']);
            for($i = 0; $i < $total_files; $i++) {
                if($_FILES['gallery_images']['error'][$i] == 0) {
                    $file_array = [
                        'name' => $_FILES['gallery_images']['name'][$i],
                        'type' => $_FILES['gallery_images']['type'][$i],
                        'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                        'error' => $_FILES['gallery_images']['error'][$i],
                        'size' => $_FILES['gallery_images']['size'][$i]
                    ];
                    $gallery_path = uploadImage($file_array, 'products/gallery');
                    if($gallery_path) {
                        $conn->query("INSERT INTO product_images (product_id, image_path) VALUES ('$product_id', '$gallery_path')");
                    }
                }
            }
        }

        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: " . getRedirectUrl());
    exit();
}

// Handle Update Product
if (isset($_POST['update_product'])) {
    $id = (int)$_POST['product_id'];
    $brand_id = (int)$_POST['brand_id'];
    $category_id = (int)$_POST['category_id'];
    $sub_category_id = !empty($_POST['sub_category_id']) ? (int)$_POST['sub_category_id'] : 'NULL';
    $title = $conn->real_escape_string($_POST['title']);
    $description = $conn->real_escape_string($_POST['description']);
    $specifications = $conn->real_escape_string($_POST['specifications']);
    $mrp = (float)$_POST['mrp'];
    $sales_price = (float)$_POST['sales_price'];
    $discount_percentage = (float)$_POST['discount_percentage'];
    $stock = (int)$_POST['stock'];
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);
    $is_price_request = isset($_POST['is_price_request']) ? 1 : 0;

    $moq = isset($_POST['moq']) ? (int)$_POST['moq'] : 1;
    $unit = isset($_POST['unit']) ? $conn->real_escape_string($_POST['unit']) : 'nos';

    // Slug handling
    $user_slug = isset($_POST['slug']) ? trim($_POST['slug']) : '';
    $base_slug = !empty($user_slug) ? createSlug($user_slug) : createSlug($_POST['title']);
    $slug = $base_slug;
    $counter = 1;
    while (true) {
        $chk_slug = $conn->real_escape_string($slug);
        $chk_res = $conn->query("SELECT id FROM products WHERE slug = '$chk_slug' AND id != $id");
        if ($chk_res && $chk_res->num_rows > 0) {
            $counter++;
            $slug = $base_slug . '-' . $counter;
        } else {
            break;
        }
    }
    $slug_escaped = $conn->real_escape_string($slug);

    // If price on request, set prices to 0
    if($is_price_request) {
        $mrp = 0;
        $sales_price = 0;
        $discount_percentage = 0;
    }

    $sku = $conn->real_escape_string($_POST['sku']);
    $hsn_code = $conn->real_escape_string($_POST['hsn_code']);

    $sql = "UPDATE products SET 
            brand_id='$brand_id',
            category_id='$category_id',
            sub_category_id=$sub_category_id, 
            title='$title', 
            slug='$slug_escaped',
            sku='$sku',
            hsn_code='$hsn_code',
            description='$description', 
            specifications='$specifications',
            mrp='$mrp', 
            sales_price='$sales_price', 
            discount_percentage='$discount_percentage', 
            stock='$stock',
            moq='$moq',
            unit='$unit',
            is_price_request='$is_price_request',
            meta_title='$meta_title',
            meta_description='$meta_description',
            meta_keywords='$meta_keywords'
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        // Handle Featured Image Update
        if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
            $featured_image = uploadImage($_FILES['featured_image'], 'products');
            if($featured_image) {
                // Delete old image
                $old_img_q = $conn->query("SELECT featured_image FROM products WHERE id='$id'");
                $old_img = $old_img_q->fetch_assoc();
                if($old_img['featured_image'] && file_exists("../../" . $old_img['featured_image'])) {
                   unlink("../../" . $old_img['featured_image']);
                }
                $conn->query("UPDATE products SET featured_image='$featured_image' WHERE id='$id'");
            }
        }

        // Handle New Gallery Images
         if (isset($_FILES['gallery_images'])) {
            $total_files = count($_FILES['gallery_images']['name']);
            for($i = 0; $i < $total_files; $i++) {
                if($_FILES['gallery_images']['error'][$i] == 0) {
                    $file_array = [
                        'name' => $_FILES['gallery_images']['name'][$i],
                        'type' => $_FILES['gallery_images']['type'][$i],
                        'tmp_name' => $_FILES['gallery_images']['tmp_name'][$i],
                        'error' => $_FILES['gallery_images']['error'][$i],
                        'size' => $_FILES['gallery_images']['size'][$i]
                    ];
                    $gallery_path = uploadImage($file_array, 'products/gallery');
                    if($gallery_path) {
                        $conn->query("INSERT INTO product_images (product_id, image_path) VALUES ('$id', '$gallery_path')");
                    }
                }
            }
        }

        $_SESSION['success'] = "Product updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating product: " . $conn->error;
    }
    header("Location: " . getRedirectUrl());
    exit();
}

// Handle Delete Product
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get all images to delete
    $result = $conn->query("SELECT featured_image FROM products WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['featured_image'] && file_exists("../../" . $row['featured_image'])) {
            unlink("../../" . $row['featured_image']);
        }
    }

    // Delete gallery images
    $gal_result = $conn->query("SELECT image_path FROM product_images WHERE product_id=$id");
    while($gal_row = $gal_result->fetch_assoc()) {
        if ($gal_row['image_path'] && file_exists("../../" . $gal_row['image_path'])) {
            unlink("../../" . $gal_row['image_path']);
        }
    }

    $sql = "DELETE FROM products WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Product deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting product: " . $conn->error;
    }
    header("Location: " . getRedirectUrl());
    exit();
}

// Handle Bulk Delete
if (isset($_POST['bulk_delete']) && isset($_POST['product_ids'])) {
    $ids = $_POST['product_ids'];
    $success_count = 0;
    
    foreach ($ids as $id) {
        $id = (int)$id;
        
        // Get images
        $result = $conn->query("SELECT featured_image FROM products WHERE id=$id");
        if ($result && $row = $result->fetch_assoc()) {
            if ($row['featured_image'] && file_exists("../../" . $row['featured_image'])) {
                unlink("../../" . $row['featured_image']);
            }
        }
        
        // Gallery images
        $gal_result = $conn->query("SELECT image_path FROM product_images WHERE product_id=$id");
        while ($gal_row = $gal_result->fetch_assoc()) {
            if ($gal_row['image_path'] && file_exists("../../" . $gal_row['image_path'])) {
                unlink("../../" . $gal_row['image_path']);
            }
        }
        
        if ($conn->query("DELETE FROM products WHERE id=$id")) {
            $success_count++;
        }
    }
    
    $_SESSION['success'] = "$success_count products deleted successfully.";
    header("Location: " . getRedirectUrl());
    exit();
}

// Handle Delete Single Gallery Image (AJAX or direct link)
if (isset($_GET['delete_image'])) {
    $image_id = (int)$_GET['delete_image'];
    $product_id = (int)$_GET['product_id'];

    $result = $conn->query("SELECT image_path FROM product_images WHERE id=$image_id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['image_path'] && file_exists("../../" . $row['image_path'])) {
            unlink("../../" . $row['image_path']);
        }
        $conn->query("DELETE FROM product_images WHERE id=$image_id");
    }
    header("Location: edit-product.php?id=" . $product_id);
    exit();
}


// Handle Status Toggle (AJAX)
if (isset($_POST['toggle_status'])) {
    header('Content-Type: application/json');
    $id = (int)$_POST['product_id'];
    $status = (int)$_POST['status'];
    
    $sql = "UPDATE products SET status = $status WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => $conn->error]);
    }
    exit();
}

$conn->close();
?>
