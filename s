<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("DB Error");

$user_id = $_SESSION['user_id'];

$pending = $conn->prepare("
    SELECT o.order_code, o.order_date, o.status,
           GROUP_CONCAT(oi.item_name SEPARATOR ', ') AS items,
           GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ? AND o.status = 'Pending'
    GROUP BY o.id
    ORDER BY o.order_date DESC
");
$pending->bind_param("i", $user_id);
$pending->execute();
$pendingResult = $pending->get_result();

$completed = $conn->prepare("
    SELECT o.order_code, o.order_date, o.status,
           GROUP_CONCAT(oi.item_name SEPARATOR ', ') AS items,
           GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ? AND o.status = 'Completed'
    GROUP BY o.id
    ORDER BY o.order_date DESC
");
$completed->bind_param("i", $user_id);
$completed->execute();
$completedResult = $completed->get_result();

$canceled = $conn->prepare("
    SELECT o.order_code, o.order_date, o.status,
           GROUP_CONCAT(oi.item_name SEPARATOR ', ') AS items,
           GROUP_CONCAT(oi.quantity SEPARATOR ', ') AS quantities
    FROM orders o
    JOIN order_items oi ON o.id = oi.order_id
    WHERE o.user_id = ? AND o.status = 'Canceled'
    GROUP BY o.id
    ORDER BY o.order_date DESC
");
$canceled->bind_param("i", $user_id);
$canceled->execute();
$canceledResult = $canceled->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Order Status</title>
  <link rel="stylesheet" href="dash.css" />
  <link rel="stylesheet" href="status.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="topbar">
  <button class="ghost-btn menu-icon" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
  </button>
</div>

<div class="sidebar" id="sidebar">
  <a href="dash.php"><i class="fa-solid fa-house"></i> Home</a>
  <a href="order.html"><i class="fa-solid fa-list"></i> Orders</a>
  <a href="status.php" class="active"><i class="fa-solid fa-clock"></i> Order Status</a>
  <a href="report.php"><i class="fa-solid fa-chart-bar"></i> Reports</a>

</div>

<div class="main-content">
  <div class="page-header">
    <h2>Order Status</h2>
    <p>Track your orders</p>
  </div>

  <div class="content-box">
    <div class="tabs">
      <button id="pendingTab" class="tab-btn active">Pending</button>
      <button id="completedTab" class="tab-btn">Completed</button>
      <button id="canceledTab" class="tab-btn">Canceled</button>
    </div>

    <div class="table-wrapper" id="pendingWrapper">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Items</th>
            <th>Qty</th>
            <th>Status / Action</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($pendingResult->num_rows === 0): ?>
          <tr><td colspan="5">No pending orders</td></tr>
        <?php else: while ($row = $pendingResult->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['order_code']) ?></td>
            <td><?= htmlspecialchars($row['order_date']) ?></td>
            <td><?= htmlspecialchars($row['items']) ?></td>
            <td><?= htmlspecialchars($row['quantities']) ?></td>
            <td>
              <span class="status pending"
                    data-order="<?= htmlspecialchars($row['order_code']) ?>">
                <?= htmlspecialchars($row['status']) ?>
              </span>
              <button class="cancel-btn"
                      data-order="<?= htmlspecialchars($row['order_code']) ?>">
                Cancel
              </button>
            </td>
          </tr>
        <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-wrapper" id="completedWrapper" style="display:none;">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Items</th>
            <th>Qty</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($completedResult->num_rows === 0): ?>
          <tr><td colspan="5">No completed orders</td></tr>
        <?php else: while ($row = $completedResult->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['order_code']) ?></td>
            <td><?= htmlspecialchars($row['order_date']) ?></td>
            <td><?= htmlspecialchars($row['items']) ?></td>
            <td><?= htmlspecialchars($row['quantities']) ?></td>
            <td><span class="status completed"><?= htmlspecialchars($row['status']) ?></span></td>
          </tr>
        <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-wrapper" id="canceledWrapper" style="display:none;">
      <table>
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Date</th>
            <th>Items</th>
            <th>Qty</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody>
        <?php if ($canceledResult->num_rows === 0): ?>
          <tr><td colspan="5">No canceled orders</td></tr>
        <?php else: while ($row = $canceledResult->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['order_code']) ?></td>
            <td><?= htmlspecialchars($row['order_date']) ?></td>
            <td><?= htmlspecialchars($row['items']) ?></td>
            <td><?= htmlspecialchars($row['quantities']) ?></td>
            <td><span class="status canceled"><?= htmlspecialchars($row['status']) ?></span></td>
          </tr>
        <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

    <div class="table-wrapper" id="completedWrapperExtra">
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
          <?php if ($completedResult->num_rows === 0): ?>
            <tr><td colspan="5">No completed orders</td></tr>
          <?php else: while ($row = $completedResult->fetch_assoc()): ?>
            <tr>
              <td><?= htmlspecialchars($row['order_code']) ?></td>
              <td><?= htmlspecialchars($row['order_date']) ?></td>
              <td><?= htmlspecialchars($row['items']) ?></td>
              <td><?= htmlspecialchars($row['quantities']) ?></td>
              <td><span class="status completed"><?= htmlspecialchars($row['status']) ?></span></td>
            </tr>
          <?php endwhile; endif; ?>
        </tbody>
      </table>
    </div>

  </div>
</div>

<script src="sidebar.js"></script>
<script src="status.js"></script>
</body>
</html>
