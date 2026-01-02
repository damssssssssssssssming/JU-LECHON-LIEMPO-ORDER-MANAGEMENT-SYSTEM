<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Not logged in"]);
    exit();
}

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $cart = file_get_contents('php://input');
    $cart = json_decode($cart, true);
    $cart = $cart['cart'] ?? [];

    if (!$cart || count($cart) === 0) {
        echo json_encode(["success" => false, "message" => "Cart is empty!"]);
        exit();
    }

    $total_price = 0;
    foreach ($cart as $item) {
        $total_price += $item['qty'] * $item['price'];
    }

    $order_code = 'ORD' . time();

    $stmt = $conn->prepare("INSERT INTO orders (order_code, user_id, order_date, status) VALUES (?, ?, NOW(), 'Pending')");
    $stmt->bind_param("si", $order_code, $user_id);
    if (!$stmt->execute()) {
        echo json_encode(["success" => false, "message" => "Failed to create order"]);
        exit();
    }

    $order_id = $stmt->insert_id;

    $stmt_items = $conn->prepare("INSERT INTO order_items (order_id, item_name, quantity, price) VALUES (?, ?, ?, ?)");
    $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE name = ?");

    foreach ($cart as $item) {
        // Insert order item
        $stmt_items->bind_param("isid", $order_id, $item['name'], $item['qty'], $item['price']);
        $stmt_items->execute();

        // Decrease product stock
        $update_stock->bind_param("is", $item['qty'], $item['name']);
        $update_stock->execute();
    }

    $stmt->close();
    $stmt_items->close();
    $update_stock->close();
    $conn->close();

    echo json_encode(["success" => true]);
}
?>
