<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/database/db_config.php';

echo "Checking 'bulk_quotes' table...\n";
$sql = "SELECT * FROM bulk_quotes";
$result = $conn->query($sql);

if ($result) {
    echo "Rows found: " . $result->num_rows . "\n";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
    }
} else {
    echo "Error querying table: " . $conn->error . "\n";
}

echo "\nChecking 'users' table (first 5)...\n";
$u_sql = "SELECT id, name, email FROM users LIMIT 5";
$u_result = $conn->query($u_sql);
while ($u_row = $u_result->fetch_assoc()) {
    print_r($u_row);
}
?>
