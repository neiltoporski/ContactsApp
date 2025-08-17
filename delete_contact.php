<?php
header('Content-Type: application/json');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Connect to the database
$conn = new mysqli('localhost', 'root', 'root', 'contactdb'); // adjust db name if needed

if ($conn->connect_error) {
	echo json_encode(['success' => false, 'error' => $conn->connect_error]);
	exit;
}

// Decode JSON input
$data = json_decode(file_get_contents("php://input"), true);

// Validate ID
if (!isset($data['id'])) {
	echo json_encode(['success' => false, 'error' => 'No ID provided']);
	exit;
}

$id = intval($data['id']);

// Prepare and execute delete
$stmt = $conn->prepare("DELETE FROM contacts WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
	echo json_encode(['success' => true]);
} else {
	echo json_encode(['success' => false, 'error' => 'Contact not found']);
}

$stmt->close();
$conn->close();
?>
