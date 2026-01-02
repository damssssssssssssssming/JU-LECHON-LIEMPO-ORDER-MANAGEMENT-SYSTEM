<?php
session_start();

if (!isset($_SESSION['user_id']) || strtolower($_SESSION['role']) !== 'admin') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("Database error");

$settings = $conn->query("SELECT * FROM store_settings LIMIT 1")->fetch_assoc();
$images = $conn->query("SELECT * FROM store_images ORDER BY uploaded_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Settings</title>
  <link rel="stylesheet" href="adminD.css">
  <link rel="stylesheet" href="As.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
<div class="topbar">
  <button class="ghost-btn menu-icon" onclick="toggleSidebar()"><i class="fa-solid fa-bars"></i></button>
  <div class="top-icons">
    <div class="profile-dropdown">
      <i class="fa-solid fa-user"></i>
      <div class="dropdown-content">
        <a href="Apf.php">Profile</a>
        <a href="logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>

<div class="sidebar" id="sidebar">
  <a href="adminD.php"><i class="fa-solid fa-house"></i> Home</a>
  <a href="Aorder.php"><i class="fa-solid fa-list"></i> Orders</a>
  <a href="Apending.php"><i class="fa-solid fa-clock"></i> Pending Orders</a>
  <a href="Acom.php"><i class="fa-solid fa-check"></i> Completed Orders</a>
  <a href="Ap.php"><i class="fa-solid fa-utensils"></i> Products</a>
  <a href="Au.php"><i class="fa-solid fa-users"></i> Users</a>
  <a href="Al.php"><i class="fa-solid fa-file-lines"></i> Logs</a>
  <a href="As.php" class="active"><i class="fa-solid fa-gear"></i> Settings</a>
</div>

<div class="layout" id="layout">
  <div class="content">
    <div class="page-header"><h2>Store Settings</h2></div>

    <div class="content-box">
      <form method="POST" action="save_store_settings.php" enctype="multipart/form-data">
        <div class="form-group">
          <label>Store Name</label>
          <input type="text" name="store_name" value="<?= htmlspecialchars($settings['store_name']) ?>" required>
        </div>
        <div class="form-group">
          <label>Contact Number</label>
          <input type="text" name="contact_number" value="<?= htmlspecialchars($settings['contact_number']) ?>" required>
        </div>
        <div class="form-group">
          <label>Address</label>
          <textarea name="address" required><?= htmlspecialchars($settings['address']) ?></textarea>
        </div>

        <div class="form-group">
          <label>Dashboard Images (Max 5)</label>
          <input type="file" name="dashboard_images[]" multiple accept="image/*">
        </div>

        <div class="dashboard-images" style="display:flex; gap:10px; flex-wrap:wrap; margin-top:10px;">
          <?php while($img = $images->fetch_assoc()): ?>
            <div style="position:relative;">
              <img src="uploads/<?= htmlspecialchars($img['filename']) ?>" style="width:120px; height:80px; object-fit:cover; border-radius:5px;">
              <a href="delete_image.php?id=<?= $img['id'] ?>" 
                 style="position:absolute; top:2px; right:2px; color:red; text-decoration:none;">&#10006;</a>
            </div>
          <?php endwhile; ?>
        </div>

        <button type="submit" class="update-btn" style="margin-top:15px;">Save Changes</button>
      </form>
    </div>
  </div>
</div>

<script src="As.js"></script>
</body>
</html>
