<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    die("Database error");
}

$settings = $conn->query("SELECT * FROM store_settings LIMIT 1")->fetch_assoc();
$images = $conn->query("SELECT filename FROM store_images ORDER BY uploaded_at DESC");

$today = date('Y-m-d');

$stmt = $conn->prepare("SELECT COUNT(*) AS total_today FROM orders WHERE user_id = ? AND DATE(order_date) = ?");
$stmt->bind_param("is", $user_id, $today);
$stmt->execute();
$totalToday = $stmt->get_result()->fetch_assoc()['total_today'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS pending_count FROM orders WHERE user_id = ? AND status = 'Pending'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pendingCount = $stmt->get_result()->fetch_assoc()['pending_count'];
$stmt->close();

$stmt = $conn->prepare("SELECT COUNT(*) AS completed_count FROM orders WHERE user_id = ? AND status = 'Completed'");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$completedCount = $stmt->get_result()->fetch_assoc()['completed_count'];
$stmt->close();

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title><?= htmlspecialchars($settings['store_name']) ?> | Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="dash.css" />
</head>
<body>
<div class="topbar">
  <button class="ghost-btn menu-icon" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
  </button>
  <div class="top-icons">
    <div class="profile-dropdown">
      <button class="ghost-btn" id="profile-btn">
        <i class="fa-solid fa-user"></i>
      </button>
      <div class="dropdown-content" id="profile-menu">
        <a href="profile.php"><i class="fa-solid fa-id-badge"></i> Profile</a>
        <a href="login.php"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
      </div>
    </div>
  </div>
</div>

<div class="layout" id="layout">
  <div class="sidebar" id="sidebar">
    <a href="dash.php"><i class="fa-solid fa-house"></i> Home</a>
    <a href="order.php"><i class="fa-solid fa-list"></i> Orders</a>
    <a href="status.php"><i class="fa-solid fa-clock"></i> Pending Orders</a>
    <a href="report.php"><i class="fa-solid fa-chart-bar"></i> Reports</a>
  </div>

  <div class="content">
    <div class="page-header">
      <h2>Welcome to <?= htmlspecialchars($settings['store_name']) ?></h2>
      <p>Manage your orders and track reports</p>
    </div>

    <div class="summary-cards">
      <div class="card">
        <h3>Total Orders Today</h3>
        <p><?= $totalToday ?></p>
      </div>
      <div class="card">
        <h3>Pending Orders</h3>
        <p><?= $pendingCount ?></p>
      </div>
      <div class="card">
        <h3>Completed Orders</h3>
        <p><?= $completedCount ?></p>
      </div>
    </div>

    <div class="content-box">
      <div class="dashboard-carousel">
        <?php while ($img = $images->fetch_assoc()): ?>
          <div class="carousel-slide">
            <img src="uploads/<?= htmlspecialchars($img['filename']) ?>" alt="Dashboard Image" />
          </div>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</div>

<script>
const slides = document.querySelectorAll('.carousel-slide');
let currentIndex = 0;

function showSlide(index) {
  slides.forEach((slide, i) => {
    slide.classList.toggle('active', i === index);
  });
}

if (slides.length > 0) {
  showSlide(currentIndex);
  setInterval(() => {
    currentIndex = (currentIndex + 1) % slides.length;
    showSlide(currentIndex);
  }, 5000); 
}
</script>
<script src="sidebar.js"></script>
<script src="dash.js"></script>

</body>
</html>
