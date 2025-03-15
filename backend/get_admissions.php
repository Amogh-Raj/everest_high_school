<?php
header("Content-Type: application/json");
include 'db_connect.php'; // Ensure this file connects to MySQL

// Check if the connection is successful
if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

// Query to fetch the latest 5 admissions
$query = "SELECT name AS student_name, joining_class AS grade_applied, 
                 father_name AS parent_name, father_phone AS parent_contact, 
                 'Pending' AS admission_status 
          FROM admissions 
          ORDER BY id DESC 
          LIMIT 5";

$result = mysqli_query($conn, $query);

if (!$result) {
    echo json_encode(["error" => "Query execution failed"]);
    exit;
}

// Fetch data and convert it to an associative array
$admissions = mysqli_fetch_all($result, MYSQLI_ASSOC);
echo json_encode($admissions);

// Close the database connection
mysqli_close($conn);
?>
