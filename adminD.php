<?php
session_start();
if(!isset($_SESSION['username']) || $_SESSION['role'] !== 'Admin'){
    header("location:login.php");
    exit();
}
$conn = new mysqli("localhost", "root", "", "posa");
if($conn->connect_error) die("DB connection error");
$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT COUNT(*) AS total_today FROM orders WHERE DATE(order_date) = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$totalToday = $stmt->get_result()->fetch_assoc()['total_today'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS pending_count FROM orders WHERE status='Pending'");
$stmt->execute();
$pendingCount = $stmt->get_result()->fetch_assoc()['pending_count'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS completed_count FROM orders WHERE status='Completed'");
$stmt->execute();
$completedCount = $stmt->get_result()->fetch_assoc()['completed_count'];
$stmt->close();

$stmt = $conn->prepare("SELECT o.id, o.order_date, o.status, GROUP_CONCAT(oi.item_name SEPARATOR ', ') AS items, GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities FROM orders o JOIN order_items oi ON o.id = oi.order_id GROUP BY o.id ORDER BY o.order_date DESC LIMIT 5");
$stmt->execute();
$latestOrders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="adminD.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<div class="topbar">
  <button class="ghost-btn menu-icon" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
  <div class="profile-dropdown" id="profileDropdown">
    <i class="fa-solid fa-user"></i>
    <div class="dropdown-content">
      <a href="Apf.php"><i class="fa-solid fa-id-badge"></i> Profile</a>
      <a href="login.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </div>
</div>

<div class="sidebar" id="sidebar">
  <a href="adminD.php" class="active"><i class="fa-solid fa-house"></i> Home</a>
  <a href="Aorder.php"><i class="fa-solid fa-list"></i> Orders</a>
  <a href="Apending.php"><i class="fa-solid fa-clock"></i> Pending Orders</a>
  <a href="Acom.php"><i class="fa-solid fa-check"></i> Completed Orders</a>
  <a href="Ap.php"><i class="fa-solid fa-utensils"></i> Products / Menu</a>
  <a href="Au.php"><i class="fa-solid fa-users"></i> User Management</a>
  <a href="Al.php"><i class="fa-solid fa-file-lines"></i> User Logs</a>
  <a href="As.php"><i class="fa-solid fa-gear"></i> Settings</a>
</div>

<div class="layout" id="layout">
  <div class="content">
    <div class="page-header">
      <h2>Admin Dashboard</h2>
      <p>Order Management Overview</p>
    </div>

    <div class="summary-cards">
      <div class="card"><h3>Total Orders Today</h3><p><?= $totalToday ?></p></div>
      <div class="card"><h3>Pending Orders</h3><p><?= $pendingCount ?></p></div>
      <div class="card"><h3>Completed Orders</h3><p><?= $completedCount ?></p></div>
    </div>

<div class="content-box">
  <h3>Orders Chart</h3>
  <div class="filter-container">
    <label for="dateRange">Select Days:</label>
    <select id="dateRange" class="filter-btn">
      <option value="7">Last 7 Days</option>
      <option value="30">Last 30 Days</option>
      <option value="90">Last 90 Days</option>
    </select>
    <button class="filter-btn apply-btn" onclick="fetchChart()">Apply</button>
  </div>
<canvas id="ordersChart"></canvas>
</div>
    <div class="content-box">
      <h3 style="margin-bottom:10px;">Latest Orders</h3>
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Items</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($latestOrders as $order): ?>
          <tr>
            <td>#<?= $order['id'] ?></td>
            <td><?= $order['order_date'] ?></td>
            <td><?= $order['items'] ?></td>
            <td><?= $order['quantities'] ?></td>
            <td>
              <span class="status <?= strtolower($order['status']) ?>"><?= $order['status'] ?></span>
            </td>
            <td><button class="update-btn">View</button></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      
    </div>

  </div>
</div>

<script src="ad.js"></script>
</body>
</html>
