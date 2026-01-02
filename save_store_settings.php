<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("Database error");

// sanitize inputs
$store_name = $conn->real_escape_string($_POST['store_name']);
$contact_number = $conn->real_escape_string($_POST['contact_number']);
$address = $conn->real_escape_string($_POST['address']);

// update store info
$conn->query("UPDATE store_settings SET store_name='$store_name', contact_number='$contact_number', address='$address' LIMIT 1");

// handle multiple images (max 5)
if (!empty($_FILES['dashboard_images']['name'][0])) {
    $existingCount = $conn->query("SELECT COUNT(*) AS cnt FROM store_images")->fetch_assoc()['cnt'];
    $maxFiles = 5 - $existingCount;

    $files = $_FILES['dashboard_images'];
    for ($i = 0; $i < count($files['name']); $i++) {
        if ($i >= $maxFiles) break;

        $filename = time() . '_' . basename($files['name'][$i]);
        $target = "uploads/" . $filename;

        if (move_uploaded_file($files['tmp_name'][$i], $target)) {
            $conn->query("INSERT INTO store_images (filename) VALUES ('$filename')");
        }
    }
}

$conn->close();

// redirect back to settings
header("Location: As.php");
exit();
