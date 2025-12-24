<?php
include 'db_config.php';


$username = $_POST['username'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);


$sql = "INSERT INTO users (username, password) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $password);


if ($stmt->execute()) {
header("Location: login.php");
} else {
echo "Username already exists";
}
?>