<?php
session_start();
require_once '../../database/db_config.php';

// Auth Check
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit();
}

// Function to handle image upload
function uploadLogo($file) {
    $target_dir = "../../assets/uploads/brands/";
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
            return "assets/uploads/brands/" . $new_filename;
        }
    }
    return false;
}

// Handle Add Brand
if (isset($_POST['add_brand'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);
    
    $logo_path = null;
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo_path = uploadLogo($_FILES['logo']);
    }

    $sql = "INSERT INTO brands (name, logo, description, meta_title, meta_description, meta_keywords) 
            VALUES ('$name', '$logo_path', '$description', '$meta_title', '$meta_description', '$meta_keywords')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Brand added successfully!";
    } else {
        $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    header("Location: index.php");
    exit();
}

// Handle Edit Brand
if (isset($_POST['edit_brand'])) {
    $id = (int)$_POST['id'];
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $meta_title = $conn->real_escape_string($_POST['meta_title']);
    $meta_description = $conn->real_escape_string($_POST['meta_description']);
    $meta_keywords = $conn->real_escape_string($_POST['meta_keywords']);
    
    $sql_update = "UPDATE brands SET name='$name', description='$description', 
                   meta_title='$meta_title', meta_description='$meta_description', 
                   meta_keywords='$meta_keywords'";

    if (isset($_FILES['logo']) && $_FILES['logo']['error'] == 0) {
        $logo_path = uploadLogo($_FILES['logo']);
        if ($logo_path) {
            $sql_update .= ", logo='$logo_path'";
            // Optional: Delete old logo
        }
    }

    $sql_update .= " WHERE id=$id";

    if ($conn->query($sql_update) === TRUE) {
        $_SESSION['success'] = "Brand updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating record: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

// Handle Delete Brand
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    // Get logo path to delete file
    $result = $conn->query("SELECT logo FROM brands WHERE id=$id");
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($row['logo'] && file_exists("../../" . $row['logo'])) {
            unlink("../../" . $row['logo']);
        }
    }

    $sql = "DELETE FROM brands WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $_SESSION['success'] = "Brand deleted successfully!";
    } else {
        $_SESSION['error'] = "Error deleting record: " . $conn->error;
    }
    header("Location: index.php");
    exit();
}

$conn->close();
?>
