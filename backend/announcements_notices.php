<?php
header("Content-Type: application/json");
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db_connect.php'; // Ensure db_connect.php initializes $conn

if (!$conn) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$request_method = $_SERVER["REQUEST_METHOD"];
$validTables = ['announcements', 'notices'];

// Handle POST (Adding announcements/notices)
if ($request_method === "POST") {
    $type = $_POST['type'] ?? '';
    $text = $_POST['text'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';

    if (empty($type) || empty($text) || empty($start_date) || empty($end_date)) {
        echo json_encode(["error" => "Missing required fields"]);
        exit;
    }

    if (!in_array($type, $validTables)) {
        echo json_encode(["error" => "Invalid type"]);
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO " . $type . " (text, start_date, end_date) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $text, $start_date, $end_date);

    echo json_encode($stmt->execute() ? ["success" => "Added successfully"] : ["error" => "Database error: " . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

// Handle GET (Fetching announcements/notices)
if ($request_method === "GET" && isset($_GET['type'])) {
    $type = $_GET['type'];
    
    if (!in_array($type, $validTables)) {
        echo json_encode(["error" => "Invalid type"]);
        exit;
    }

    $stmt = $conn->prepare("SELECT id, text, start_date, end_date FROM " . $type . " ORDER BY start_date DESC");
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode($data);
    
    $stmt->close();
    $conn->close();
    exit;
}

// Handle DELETE (Deleting announcements/notices)
if ($request_method === "DELETE") {
    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['type'], $data['id'])) {
        echo json_encode(["error" => "Missing or invalid data"]);
        exit;
    }

    $type = $data['type'];
    $id = $data['id'];

    if (!in_array($type, $validTables)) {
        echo json_encode(["error" => "Invalid type"]);
        exit;
    }

    $stmt = $conn->prepare("DELETE FROM " . $type . " WHERE id = ?");
    $stmt->bind_param("i", $id);

    echo json_encode($stmt->execute() ? ["success" => "Deleted successfully"] : ["error" => "Database error: " . $stmt->error]);
    $stmt->close();
    $conn->close();
    exit;
}

// Invalid request method
echo json_encode(["error" => "Invalid request method"]);
exit;
?>
