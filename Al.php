<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT ul.id, u.username, ul.action, ul.order_code, ul.status, ul.created_at
        FROM user_logs ul
        JOIN users u ON ul.user_id = u.id
        ORDER BY ul.created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - User Logs</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="adminD.css">
  <link rel="stylesheet" href="Al.css">
</head>
<body>

<div class="topbar">
  <button class="ghost-btn menu-icon" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
  </button>
  <div class="top-icons">
    <div class="profile-dropdown">
      <i class="fa-solid fa-user"></i>
      <div class="dropdown-content">
        <a href="Apf.php">Profile</a>
        <a href="login.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<div class="sidebar" id="sidebar">
  <a href="adminD.php"><i class="fa-solid fa-house"></i> Home</a>
  <a href="Aorder.php"><i class="fa-solid fa-list"></i> Orders</a>
  <a href="Apending.php"><i class="fa-solid fa-clock"></i> Pending Orders</a>
  <a href="Acom.php"><i class="fa-solid fa-check"></i> Completed Orders</a>
  <a href="Ap.php"><i class="fa-solid fa-utensils"></i> Products / Menu</a>
  <a href="Au.php"><i class="fa-solid fa-users"></i> User Management</a>
  <a href="Al.php" class="active"><i class="fa-solid fa-file-lines"></i> User Logs</a>
  <a href="As.php"><i class="fa-solid fa-gear"></i> Settings</a>
</div>

<div class="layout" id="layout">
  <div class="content">
    <div class="page-header">
      <h2>User Activity Logs</h2>
      <p>Monitor staff activity and accountability</p>
    </div>

    <div class="content-box">
      <table>
        <thead>
          <tr>
            <th>User</th>
            <th>Action</th>
            <th>Order #</th>
            <th>Status</th>
            <th>Date & Time</th>
          </tr>
        </thead>
        <tbody id="logsTableBody">
          <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="5">No logs found</td></tr>
          <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['action']) ?></td>
                <td><?= $row['order_code'] ? '#' . htmlspecialchars($row['order_code']) : '-' ?></td>
                <td><?= $row['status'] ? htmlspecialchars($row['status']) : '-' ?></td>
                <td><?= date("Y-m-d h:i A", strtotime($row['created_at'])) ?></td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="Al.js"></script>
</body>
</html>
