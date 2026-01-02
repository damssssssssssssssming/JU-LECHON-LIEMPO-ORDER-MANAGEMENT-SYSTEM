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
$stmt = $conn->prepare("SELECT username, email, role, profile_pic FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Profile</title>
  <link rel="stylesheet" href="adminD.css">
  <link rel="stylesheet" href="Apf.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
  <a href="Ap.php"><i class="fa-solid fa-utensils"></i> Products / Menu</a>
  <a href="Au.php"><i class="fa-solid fa-users"></i> User Management</a>
  <a href="Al.php"><i class="fa-solid fa-file-lines"></i> User Logs</a>
  <a href="As.php"><i class="fa-solid fa-gear"></i> Settings</a>
</div>

<div class="layout" id="layout">
  <div class="content">
    <div class="page-header">
      <h2>Admin Profile</h2>
      <p>View and update your account details</p>
    </div>

    <div class="profile-card">
      <div class="avatar">
        <img src="uploads/<?= htmlspecialchars($user['profile_pic'] ?? 'default-avatar.png') ?>" alt="Profile Picture">
      </div>

      <div class="profile-info">
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
        <p><strong>Role:</strong> <?= htmlspecialchars($user['role']) ?></p>
        <p><strong>Status:</strong> Active</p>

        <button id="editProfileBtn" class="update-btn">
          <i class="fa-solid fa-pen"></i> Edit
        </button>
      </div>
    </div>

    <form id="editProfileForm" class="hidden" method="POST" action="updateProfile.php" enctype="multipart/form-data">
      <div class="form-group">
        <label for="profileEmail">Email</label>
        <input type="email" id="profileEmail" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
      <div class="form-group">
        <label for="profilePassword">New Password</label>
        <input type="password" id="profilePassword" name="password" placeholder="Leave blank to keep current">
      </div>
      <div class="form-group">
        <label for="profilePic">Profile Picture</label>
        <input type="file" id="profilePic" name="profile_pic" accept="image/*">
      </div>
      <button type="submit" class="update-btn">Save Changes</button>
    </form>

  </div>
</div>

<script src="Apf.js"></script>
</body>
</html>
