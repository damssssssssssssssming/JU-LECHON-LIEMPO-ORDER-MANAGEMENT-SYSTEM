<?php
session_start();
$conn = new mysqli("localhost", "root", "", "posa");
if($conn->connect_error){
    echo json_encode([]);
    exit;
}

$result = $conn->query("SELECT id, stock FROM products");
$stocks = [];
if($result->num_rows > 0){
    while($row = $result->fetch_assoc()){
        $stocks[$row['id']] = intval($row['stock']);
    }
}

echo json_encode($stocks);
$conn->close();
?>
