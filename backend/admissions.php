<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
       $name = $_POST['name'];
    $age = $_POST['age'];
    $dob = $_POST['dob'];
    $mother_tongue = $_POST['mother_tongue'];
    $disability = $_POST['disability'];
    $gender = $_POST['gender'];
    $joining_class = $_POST['joining_class'];
    $aadhar = $_POST['aadhar'];
    $religion = $_POST['religion'];
    $address = $_POST['address'];
    $mother_name = $_POST['mother_name'];
    $father_name = $_POST['father_name'];
    $mother_phone = $_POST['mother_phone'];
    $father_phone = $_POST['father_phone'];
    $father_occupation = $_POST['father_occupation'];
    $mother_occupation = $_POST['mother_occupation'];
    $father_email = $_POST['father_email'];
    $mother_email = $_POST['mother_email'];
    $family_income = $_POST['family_income'];
    $siblings = $_POST['siblings'];

    // File upload
    $target_dir = "uploads/";
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    $target_file = $target_dir . basename($_FILES["photo"]["name"]);
    move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

    
    // Prepare SQL Statement
    $stmt = $conn->prepare("INSERT INTO admissions (name, age, dob, mother_tongue, disability, gender, joining_class, aadhar, religion, address, mother_name, father_name, mother_phone, father_phone, father_occupation, mother_occupation, father_email, mother_email, family_income, siblings, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    if (!$stmt) {
        die("<h2>Error in preparing statement:</h2> " . $conn->error);
    }

    // Bind Parameters
    $stmt->bind_param("sisssssssssssssssdiss", $name, $age, $dob, $mother_tongue, $disability, $gender, $joining_class, $aadhar, $religion, $address, $mother_name, $father_name, $mother_phone, $father_phone, $father_occupation, $mother_occupation, $father_email, $mother_email, $family_income, $siblings, $target_file);

    // Execute Query and Show Result
    if ($stmt->execute()) {
        echo "Data Inserted Successfully!";
    } else {
        echo "Error Inserting Data:" . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
