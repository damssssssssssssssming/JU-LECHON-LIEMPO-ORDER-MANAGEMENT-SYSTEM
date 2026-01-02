<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "posa");
$id = (int)$_GET['id'];
$filename = $conn->query("SELECT filename FROM store_images WHERE id=$id")->fetch_assoc()['filename'];
if($filename) {
    unlink("uploads/$filename");
    $conn->query("DELETE FROM store_images WHERE id=$id");
}
$conn->close();
header("Location: As.php");
exit();
