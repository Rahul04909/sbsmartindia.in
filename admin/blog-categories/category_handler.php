<?php
session_start();
require_once '../../database/db_config.php';

// Auth Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Function to handle image upload
function uploadImage($file, $folder) {
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

// Handle Add Category
if (isset($_POST['add_category'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $slug = $conn->real_escape_string($_POST['slug']);
    $description = $conn->real_escape_string($_POST['description']);
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);

    // Check if slug exists
    $check = $conn->query("SELECT id FROM blog_categories WHERE slug = '$slug'");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Slug already exists. Please choose a different name or slug.";
        header("Location: add-category.php");
        exit();
    }

    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = uploadImage($_FILES['image'], 'blog_categories');
    }

    $sql = "INSERT INTO blog_categories (name, slug, description, image, meta_title, meta_description, meta_keywords) 
            VALUES ('$name', '$slug', '$description', '$image_path', '$meta_title', '$meta_description', '$meta_keywords')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Category added successfully!";
        header("Location: index.php");
    } else {
        $_SESSION['error'] = "Error adding category: " . $conn->error;
        header("Location: add-category.php");
    }
    exit();
}

// Handle Update Category
if (isset($_POST['update_category'])) {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $slug = $conn->real_escape_string($_POST['slug']);
    $description = $conn->real_escape_string($_POST['description']);
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);

    // Check if slug exists for other categories
    $check = $conn->query("SELECT id FROM blog_categories WHERE slug = '$slug' AND id != $id");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Slug already exists. Please choose a different name or slug.";
        header("Location: edit-category.php?id=$id");
        exit();
    }

    // Handle Image Update
    $image_update_sql = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = uploadImage($_FILES['image'], 'blog_categories');
        if ($image_path) {
            // Delete old image
            $old_img_q = $conn->query("SELECT image FROM blog_categories WHERE id='$id'");
            $old_img = $old_img_q->fetch_assoc();
            if ($old_img['image'] && file_exists("../../" . $old_img['image'])) {
                unlink("../../" . $old_img['image']);
            }
            $image_update_sql = ", image='$image_path'";
        }
    }

    $sql = "UPDATE blog_categories SET 
            name='$name', 
            slug='$slug', 
            description='$description',
            meta_title='$meta_title',
            meta_description='$meta_description',
            meta_keywords='$meta_keywords'
            $image_update_sql
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Category updated successfully!";
        header("Location: index.php");
    } else {
        $_SESSION['error'] = "Error updating category: " . $conn->error;
        header("Location: edit-category.php?id=$id");
    }
    exit();
}

$conn->close();
?>
