<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') exit(json_encode([]));

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) exit(json_encode([]));

$days = isset($_GET['days']) ? intval($_GET['days']) : 7;

$dates = [];
$pending = [];
$completed = [];
$canceled = [];

for ($i = $days-1; $i >= 0; $i--) {
    $date = date('Y-m-d', strtotime("-$i days"));
    $dates[] = $date;

    $stmt = $conn->prepare("SELECT 
        SUM(status='Pending') as p,
        SUM(status='Completed') as c,
        SUM(status='Canceled') as x
        FROM orders WHERE DATE(order_date)=?");
    $stmt->bind_param("s", $date);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $pending[] = intval($row['p']);
    $completed[] = intval($row['c']);
    $canceled[] = intval($row['x']);
    $stmt->close();
}

$conn->close();

echo json_encode([
    'dates' => $dates,
    'pending' => $pending,
    'completed' => $completed,
    'canceled' => $canceled
]);
