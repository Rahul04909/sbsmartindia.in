<?php
require_once '../../database/db_config.php';

if(isset($_POST['category_id'])) {
    $category_id = (int)$_POST['category_id'];
    
    $sql = "SELECT id, name FROM product_sub_categories WHERE category_id = $category_id ORDER BY name ASC";
    $result = $conn->query($sql);
    
    echo '<option value="">Select Sub Category</option>';
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<option value="'.$row['id'].'">'.htmlspecialchars($row['name']).'</option>';
        }
    }
}
?>
