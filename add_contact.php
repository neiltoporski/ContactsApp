<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Read and decode JSON
$data = json_decode(file_get_contents("php://input"), true);

$required = ['firstName', 'lastName'];
foreach ($required as $field) {
	if (empty($data[$field])) {
		echo json_encode(['success' => false, 'error' => "$field is required"]);
		exit;
	}
}

// DB setup
$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbname = 'contactdb';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
	echo json_encode(['success' => false, 'error' => $conn->connect_error]);
	exit;
}

$stmt = $conn->prepare("INSERT INTO contacts (firstName, lastName, address, city, state, zip, email, phone) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param(
	"ssssssss",
	$data['firstName'], $data['lastName'],
	$data['address'], $data['city'], $data['state'],
	$data['zip'], $data['email'], $data['phone']
);

if ($stmt->execute()) {
	echo json_encode(['success' => true]);
} else {
	echo json_encode(['success' => false, 'error' => $stmt->error]);
}

$stmt->close();
$conn->close();
?>
