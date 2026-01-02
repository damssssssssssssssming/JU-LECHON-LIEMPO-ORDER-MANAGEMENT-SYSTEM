<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("DB connection error");

$stmt = $conn->prepare("SELECT COUNT(*) AS total_orders FROM orders WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$totalOrders = $stmt->get_result()->fetch_assoc()['total_orders'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS completed_orders FROM orders WHERE user_id = ? AND status = 'Completed'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$completedOrders = $stmt->get_result()->fetch_assoc()['completed_orders'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS pending_orders FROM orders WHERE user_id = ? AND status = 'Pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pendingOrders = $stmt->get_result()->fetch_assoc()['pending_orders'];
$stmt->close();

$ordersQuery = $conn->prepare("
    SELECT o.order_code, o.order_date, o.status,
           GROUP_CONCAT(oi.item_name SEPARATOR ', ') AS items,
           GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ?
    GROUP BY o.id
    ORDER BY o.order_date DESC
");
$ordersQuery->bind_param("i", $user_id);
$ordersQuery->execute();
$ordersResult = $ordersQuery->get_result();
$ordersQuery->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Report</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="dash.css" />
  <link rel="stylesheet" href="report.css" />
</head>
<body>

  <div class="topbar">
    <button class="ghost-btn menu-icon" onclick="toggleSidebar()">
      <i class="fa-solid fa-bars"></i>
    </button>
  </div>

  <div class="layout">
    <div class="sidebar" id="sidebar">
      <a href="dash.php"><i class="fa-solid fa-house"></i> Home</a>
      <a href="order.php"><i class="fa-solid fa-list"></i> Orders</a>
      <a href="status.php"><i class="fa-solid fa-clock"></i> Pending Orders</a>
      <a href="report.php"><i class="fa-solid fa-chart-bar"></i> Reports</a>
    </div>

    <div class="main-content">
      <div class="page-header">
        <h2>Reports</h2>
        <p>View and manage sales reports</p>
      </div>

      <div class="content-box">
        <h3>Summary</h3>
        <p>Total Orders: <strong><?= $totalOrders ?></strong></p>
        <p>Completed Orders: <strong><?= $completedOrders ?></strong></p>
        <p>Pending Orders: <strong><?= $pendingOrders ?></strong></p>

        <h3>Order Report Table</h3>
        <table>
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Date</th>
              <th>Items</th>
              <th>Quantity</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php if ($ordersResult->num_rows === 0): ?>
              <tr><td colspan="5">No orders yet</td></tr>
            <?php else: while ($row = $ordersResult->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['order_code']) ?></td>
                <td><?= htmlspecialchars($row['order_date']) ?></td>
                <td><?= htmlspecialchars($row['items']) ?></td>
                <td><?= htmlspecialchars($row['quantities']) ?></td>
                <td>
                  <span class="status <?= strtolower($row['status']) ?>">
                    <?= htmlspecialchars($row['status']) ?>
                  </span>
                </td>
              </tr>
            <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <script src="sidebar.js"></script>
</body>
</html>
