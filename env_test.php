<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
echo "PHP Version: " . phpversion() . "<br>";
echo "Current File: " . __FILE__ . "<br>";
echo "Included files: <pre>";
print_r(get_included_files());
echo "</pre>";
?>
