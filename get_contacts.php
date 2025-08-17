<?php
header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$user = 'root';
$pass = 'root';
$dbname = 'contactdb';

$conn = new mysqli($host, $user, $pass, $dbname);
if ($conn->connect_error) {
	http_response_code(500);
	echo json_encode(["error" => "Connection failed: " . $conn->connect_error]);
	exit;
}

$sql = "SELECT * FROM contacts";
$result = $conn->query($sql);

if (!$result) {
	http_response_code(500);
	echo json_encode(["error" => "Query error: " . $conn->error]);
	exit;
}

$contacts = [];
while ($row = $result->fetch_assoc()) {
	$contacts[] = $row;
}
$conn->close();

echo json_encode($contacts);
?>
