<?php
session_start();
include 'config.php';


$username = $_POST['username'];
$password = $_POST['password'];


$sql = "SELECT * FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows === 1) {
$row = $result->fetch_assoc();
if (password_verify($password, $row['password'])) {
$_SESSION['user'] = $row['username'];
header("Location: dashboard.php");
} else {
echo "Invalid password";
}
} else {
echo "User not found";
}
?>