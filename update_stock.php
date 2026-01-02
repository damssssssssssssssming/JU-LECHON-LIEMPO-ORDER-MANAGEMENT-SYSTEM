<?php
session_start();
header('Content-Type: application/json');

// Make sure user is logged in as admin
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Not logged in']);
    exit;
}

// Get JSON from JS
$data = json_decode(file_get_contents('php://input'), true);
if (!$data || !isset($data['id'], $data['action'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid request']);
    exit;
}

$id = intval($data['id']);
$action = $data['action'];

// DB connection
$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'DB connection error']);
    exit;
}

// Get current stock
$stmt = $conn->prepare("SELECT stock FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Item not found']);
    exit;
}

$row = $result->fetch_assoc();
$stock = intval($row['stock']);

// Update stock
if ($action === 'increase') $stock++;
if ($action === 'decrease') $stock = max(0, $stock - 1);

$stmt = $conn->prepare("UPDATE products SET stock = ? WHERE id = ?");
$stmt->bind_param("ii", $stock, $id);
$stmt->execute();

echo json_encode(['success' => true, 'stock' => $stock]);

$stmt->close();
$conn->close();
