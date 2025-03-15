<?php
// Include database connection file
include 'db_connect.php';

// Test database connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Database Connected Successfully!";
}

// Close connection
$conn->close();
?>
