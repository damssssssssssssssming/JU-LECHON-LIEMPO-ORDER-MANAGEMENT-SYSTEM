<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') exit(json_encode([]));

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) exit(json_encode([]));

$days = isset($_GET['days']) ? intval($_GET['days']) : 7;

$labels = [];
$values = [];

for ($i = $days-1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $labels[] = $date;

    $stmt = $conn->prepare("
        SELECT 
            COUNT(IF(status='Pending',1,NULL)) AS pending_count,
            COUNT(IF(status='Completed',1,NULL)) AS completed_count,
            COUNT(IF(status='Canceled',1,NULL)) AS canceled_count
        FROM orders
        WHERE DATE(order_date) = ?
    ");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    // Example: total orders per day
    $totalOrders = intval($row['pending_count']) + intval($row['completed_count']) + intval($row['canceled_count']);
    $values[] = $totalOrders;
}

$conn->close();

echo json_encode([
    'labels' => $labels,
    'values' => $values
]);
