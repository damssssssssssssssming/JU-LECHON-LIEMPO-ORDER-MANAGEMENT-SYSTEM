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

$sql = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Admin - User Management</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
  <link rel="stylesheet" href="adminD.css" />
  <link rel="stylesheet" href="Au.css" />
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
        <a href="#">Profile</a>
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
  <a href="Au.php" class="active"><i class="fa-solid fa-users"></i> User Management</a>
  <a href="Al.php"><i class="fa-solid fa-file-lines"></i> User Logs</a>
  <a href="As.php"><i class="fa-solid fa-gear"></i> Settings</a>
</div>

<div class="layout" id="layout">
  <div class="content">
    <div class="page-header">
      <h2>User Management</h2>
      <p>Manage system users</p>
    </div>

    <div class="content-box">
      <button id="addUserBtn" class="update-btn" style="margin-bottom:15px;">
        <i class="fa-solid fa-user-plus" title="Add New User"></i>
      </button>

      <table>
        <thead>
          <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Registered At</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody id="usersTableBody">
          <?php if ($result->num_rows === 0): ?>
            <tr><td colspan="5">No users found</td></tr>
          <?php else: ?>
            <?php while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td>
                  <button class="edit-btn" data-userid="<?= $row['id'] ?>" title="Edit">
                    <i class="fa-solid fa-pen"></i>
                  </button>
                  <button class="toggle-btn" data-userid="<?= $row['id'] ?>" title="Deactivate">
                    <i class="fa-solid fa-user-slash"></i>
                  </button>
                </td>
              </tr>
            <?php endwhile; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<script src="Au.js"></script>
</body>
</html>
