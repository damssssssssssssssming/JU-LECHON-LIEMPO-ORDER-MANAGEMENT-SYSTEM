<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>User Profile</title>

  <link rel="stylesheet" href="dash.css">
  <link rel="stylesheet" href="p.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>

<div class="topbar">
  <button class="ghost-btn">
    <i class="fa-solid fa-bars"></i>
  </button>

  <div class="top-icons">
    <div class="profile-dropdown">
      <button class="ghost-btn">
        <i class="fa-solid fa-user"></i>
      </button>
      <div class="dropdown-content">
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<div class="sidebar active">
  <a href="dash.html"><i class="fa-solid fa-house"></i> Home</a>
  <a href="profile.php" class="active"><i class="fa-solid fa-user"></i> Profile</a>
  <a href="order.html"><i class="fa-solid fa-list"></i> Orders</a>
  <a href="status.php"><i class="fa-solid fa-clock"></i> Pending Orders</a>
  <a href="report.html"><i class="fa-solid fa-chart-bar"></i> Reports</a>
</div>

<div class="layout shift">
  <div class="content">
    <div class="content-box profile-box">

      <h2>My Profile</h2>
      <p>Your account information</p>

      <div class="profile-card">
        <div class="avatar">
          <i class="fa-solid fa-user-circle"></i>
        </div>

        <div class="profile-info">
          <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
          <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
          <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
          <p><strong>Status:</strong> Active</p>
        </div>
      </div>

    </div>
  </div>
</div>

</body>
</html>
