<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
if (!isset($_SESSION['user_id'])) exit(json_encode(['success' => false, 'message' => 'Not logged in']));
if (!$data || !isset($data['order_code'])) exit(json_encode(['success' => false, 'message' => 'Invalid request']));

$order_code = $data['order_code'];
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) exit(json_encode(['success' => false, 'message' => 'DB error']));

$stmt = $conn->prepare("UPDATE orders SET status = 'Completed' WHERE order_code = ? AND user_id = ?");
$stmt->bind_param("si", $order_code, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update order']);
}

$stmt->close();
$conn->close();
exit;
?>
