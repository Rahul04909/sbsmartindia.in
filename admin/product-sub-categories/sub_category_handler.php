<?php
session_start();
require_once '../../database/db_config.php';

// Auth Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Function to handle image upload
function uploadImage($file) {
    $target_dir = "../../assets/uploads/sub-categories/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $file_extension = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is a actual image or fake image
    $check = getimagesize($file["tmp_name"]);
    if($check !== false) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return "assets/uploads/sub-categories/" . $new_filename;
        }
    }
    return false;
}

// Handle Add Sub-Category
if (isset($_POST['add_sub_category'])) {
    $category_id = (int)$_POST['category_id'];
    $name = $conn->real_escape_string($_POST['name']);
    
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = uploadImage($_FILES['image']);
    }

    $sql = "INSERT INTO product_sub_categories (category_id, name, image) 
            VALUES ($category_id, '$name', '$image_path')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Sub-Category added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: index.php");
    exit();
}

// Handle Edit Sub-Category
if (isset($_POST['edit_sub_category'])) {
    $id = (int)$_POST['id'];
    $category_id = (int)$_POST['category_id'];
    $name = $conn->real_escape_string($_POST['name']);
    
    $sql_update = "UPDATE product_sub_categories SET category_id=$category_id, name='$name'";

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_path = uploadImage($_FILES['image']);
        if ($image_path) {
            $sql_update .= ", image='$image_path'";
            // Optional: Delete old image logic here if needed
        }
    }

    $sql_update .= " WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['success'] = "Sub-Category updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating record: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

// Handle Delete Sub-Category
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get image path to delete file
    $result = $conn->query("SELECT image FROM product_sub_categories WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['image'] && file_exists("../../" . $row['image'])) {
            unlink("../../" . $row['image']);
        }
    }

    $sql = "DELETE FROM product_sub_categories WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Sub-Category deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

$conn->close();
?>
