<?php
session_start();
header('Content-Type: application/json');

// Make sure admin is logged in
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['id'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = intval($data['id']);

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection error']);
    exit;
}

// Get current status
$stmt = $conn->prepare("SELECT status FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
    exit;
}

$row = $result->fetch_assoc();
$status = ($row['status'] === 'Available') ? 'Disabled' : 'Available';

// Update status
$stmt = $conn->prepare("UPDATE products SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $id);
$stmt->execute();

echo json_encode(['success' => true, 'status' => $status]);

$stmt->close();
$conn->close();
