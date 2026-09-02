<?php
/**
 * Database Migration Script: Add and Populate `slug` Column in `products` Table
 * Location: database/sql/add_slug_column.php
 */

$db_config_path = __DIR__ . '/../db_config.php';
if (file_exists($db_config_path)) {
    require_once $db_config_path;
} else {
    die("Error: db_config.php not found at $db_config_path\n");
}

$url_helper_path = __DIR__ . '/../../includes/url_helper.php';
if (file_exists($url_helper_path)) {
    require_once $url_helper_path;
}

if (!isset($conn) || $conn->connect_error) {
    die("Error: Database connection variable \$conn not set or connection failed.\n");
}

echo "Starting migration: Add 'slug' column to products table...\n";

// 1. Check if 'slug' column already exists in 'products'
$check_col = $conn->query("SHOW COLUMNS FROM products LIKE 'slug'");
if ($check_col->num_rows == 0) {
    $sql = "ALTER TABLE products ADD COLUMN slug VARCHAR(255) NULL AFTER title";
    if ($conn->query($sql) === TRUE) {
        echo "Column 'slug' added successfully.\n";
    } else {
        die("Error adding 'slug' column: " . $conn->error . "\n");
    }
} else {
    echo "Column 'slug' already exists in 'products'.\n";
}

// Helper slug generator fallback if url_helper wasn't loaded
if (!function_exists('createSlug')) {
    function createSlug($string) {
        $slug = mb_strtolower(trim($string), 'UTF-8');
        $slug = str_replace('&', 'and', $slug);
        $slug = preg_replace('/[^\w\s-]/u', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        $slug = trim($slug, '-');
        return !empty($slug) ? $slug : 'product';
    }
}

// 2. Fetch all products to update missing or empty slugs
$res = $conn->query("SELECT id, title, slug FROM products");
$updated_count = 0;

if ($res && $res->num_rows > 0) {
    while ($row = $res->fetch_assoc()) {
        $id = (int)$row['id'];
        $title = $row['title'];
        $current_slug = trim((string)$row['slug']);
        
        if (empty($current_slug)) {
            $base_slug = createSlug($title);
            $slug = $base_slug;
            $counter = 1;
            
            // Ensure unique slug
            while (true) {
                $check_sql = "SELECT id FROM products WHERE slug = '" . $conn->real_escape_string($slug) . "' AND id != $id";
                $check_res = $conn->query($check_sql);
                if ($check_res && $check_res->num_rows > 0) {
                    $counter++;
                    $slug = $base_slug . '-' . $counter;
                } else {
                    break;
                }
            }
            
            $update_sql = "UPDATE products SET slug = '" . $conn->real_escape_string($slug) . "' WHERE id = $id";
            if ($conn->query($update_sql)) {
                $updated_count++;
            } else {
                echo "Error updating slug for Product ID $id: " . $conn->error . "\n";
            }
        }
    }
    echo "Populated $updated_count product slug(s).\n";
}

// 3. Add UNIQUE Index to 'slug' column if not exists
$check_index = $conn->query("SHOW INDEX FROM products WHERE Key_name = 'slug_unique_idx' OR Key_name = 'slug'");
if ($check_index && $check_index->num_rows == 0) {
    // Check for any remaining duplicates before creating unique index
    $dup_check = $conn->query("SELECT slug, COUNT(*) as cnt FROM products WHERE slug IS NOT NULL AND slug != '' GROUP BY slug HAVING cnt > 1");
    if ($dup_check && $dup_check->num_rows == 0) {
        $sql_index = "ALTER TABLE products ADD UNIQUE INDEX slug_unique_idx (slug)";
        if ($conn->query($sql_index) === TRUE) {
            echo "Unique index on 'slug' created successfully.\n";
        } else {
            echo "Warning: Could not create unique index on 'slug': " . $conn->error . "\n";
        }
    }
}

echo "Migration finished successfully.\n";
?>
