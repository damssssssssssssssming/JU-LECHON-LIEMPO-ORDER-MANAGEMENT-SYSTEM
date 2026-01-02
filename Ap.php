<?php
session_start();
if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("DB Error");

$products = $conn->query("SELECT * FROM products ORDER BY name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin - Products/Menu</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<link rel="stylesheet" href="adminD.css">
<link rel="stylesheet" href="Ap.css">
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
      <h2>Products / Menu</h2>
      <p>Manage available items and their availability</p>
    </div>

    <div class="content-box">
      <table>
        <thead>
          <tr>
            <th>Item</th>
            <th>Price</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>

        <tbody>
        <?php while ($row = $products->fetch_assoc()): ?>
          <tr data-id="<?= $row['id'] ?>">
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>₱<?= number_format($row['price'], 2) ?></td>
            <td>
              <span class="status <?= $row['status'] === 'available' ? 'available' : 'disabled' ?>">
                <?= ucfirst($row['status']) ?>
              </span>
            </td>
            <td style="display:flex; gap:6px; align-items:center;">
              <button class="toggle-btn">
                <?= $row['status'] === 'available' ? 'Disable' : 'Enable' ?>
              </button>
              <button class="stock-btn" data-action="decrease">-</button>
              <span class="stock-count"><?= (int)$row['stock'] ?></span>
              <button class="stock-btn" data-action="increase">+</button>
            </td>
          </tr>
        <?php endwhile; ?>
        </tbody>

      </table>
    </div>
  </div>
</div>

<script src="Ap.js"></script>
</body>
</html>
