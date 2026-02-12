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

// Handle Add Blog
if (isset($_POST['add_blog'])) {
    $title = $conn->real_escape_string($_POST['title']);
    $slug = $conn->real_escape_string($_POST['slug']);
    $category_id = intval($_POST['category_id']);
    $content = $conn->real_escape_string($_POST['content']);
    $status = intval($_POST['status']);
    
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);
    $schema_markup = $conn->real_escape_string($_POST['schema_markup']);

    // Check if slug exists
    $check = $conn->query("SELECT id FROM blogs WHERE slug = '$slug'");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Slug already exists. Please choose a different title or slug.";
        header("Location: add-blog.php");
        exit();
    }

    $image_path = null;
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $image_path = uploadImage($_FILES['featured_image'], 'blogs');
    }

    $sql = "INSERT INTO blogs (category_id, title, slug, content, featured_image, meta_title, meta_description, meta_keywords, schema_markup, status) 
            VALUES ('$category_id', '$title', '$slug', '$content', '$image_path', '$meta_title', '$meta_description', '$meta_keywords', '$schema_markup', '$status')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Blog post added successfully!";
        header("Location: index.php");
    } else {
        $_SESSION['error'] = "Error adding blog post: " . $conn->error;
        header("Location: add-blog.php");
    }
    exit();
}

// Handle Update Blog
if (isset($_POST['update_blog'])) {
    $id = (int)$_POST['id'];
    $title = $conn->real_escape_string($_POST['title']);
    $slug = $conn->real_escape_string($_POST['slug']);
    $category_id = intval($_POST['category_id']);
    $content = $conn->real_escape_string($_POST['content']);
    $status = intval($_POST['status']);
    
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);
    $schema_markup = $conn->real_escape_string($_POST['schema_markup']);

    // Check if slug exists for other blogs
    $check = $conn->query("SELECT id FROM blogs WHERE slug = '$slug' AND id != $id");
    if ($check->num_rows > 0) {
        $_SESSION['error'] = "Slug already exists. Please choose a different title or slug.";
        header("Location: edit-blog.php?id=$id");
        exit();
    }

    // Handle Image Update
    $image_update_sql = "";
    if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] == 0) {
        $image_path = uploadImage($_FILES['featured_image'], 'blogs');
        if ($image_path) {
            // Delete old image
            $old_img_q = $conn->query("SELECT featured_image FROM blogs WHERE id='$id'");
            $old_img = $old_img_q->fetch_assoc();
            if ($old_img['featured_image'] && file_exists("../../" . $old_img['featured_image'])) {
                unlink("../../" . $old_img['featured_image']);
            }
            $image_update_sql = ", featured_image='$image_path'";
        }
    }

    $sql = "UPDATE blogs SET 
            category_id='$category_id',
            title='$title', 
            slug='$slug', 
            content='$content',
            status='$status',
            meta_title='$meta_title',
            meta_description='$meta_description',
            meta_keywords='$meta_keywords',
            schema_markup='$schema_markup'
            $image_update_sql
            WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Blog post updated successfully!";
        header("Location: index.php");
    } else {
        $_SESSION['error'] = "Error updating blog post: " . $conn->error;
        header("Location: edit-blog.php?id=$id");
    }
    exit();
}

$conn->close();
?>
