<?php
session_start();
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($_SESSION['user_id'])) {
    exit(json_encode(['success' => false, 'message' => 'Not logged in']));
}

if (!$data || !isset($data['order_code'])) {
    exit(json_encode(['success' => false, 'message' => 'Invalid request']));
}

$order_code = $data['order_code'];
$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    exit(json_encode(['success' => false, 'message' => 'DB error']));
}

$stmt_order = $conn->prepare("SELECT id FROM orders WHERE order_code = ? AND user_id = ?");
$stmt_order->bind_param("si", $order_code, $user_id);
$stmt_order->execute();
$result_order = $stmt_order->get_result();
if ($result_order->num_rows === 0) {
    echo json_encode(['success' => false, 'message' => 'Order not found']);
    exit();
}

$order = $result_order->fetch_assoc();
$order_id = $order['id'];

$stmt_items = $conn->prepare("SELECT item_name, quantity FROM order_items WHERE order_id = ?");
$stmt_items->bind_param("i", $order_id);
$stmt_items->execute();
$result_items = $stmt_items->get_result();

$update_stock = $conn->prepare("UPDATE products SET stock = stock + ? WHERE name = ?");

while ($row = $result_items->fetch_assoc()) {
    $qty = $row['quantity'];
    $name = $row['item_name'];
    $update_stock->bind_param("is", $qty, $name);
    $update_stock->execute();
}

$stmt_items->close();
$update_stock->close();

$stmt = $conn->prepare("UPDATE orders SET status = 'Canceled' WHERE order_code = ? AND user_id = ?");
$stmt->bind_param("si", $order_code, $user_id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to cancel order']);
}

$stmt->close();
$stmt_order->close();
$conn->close();
exit;
?>
