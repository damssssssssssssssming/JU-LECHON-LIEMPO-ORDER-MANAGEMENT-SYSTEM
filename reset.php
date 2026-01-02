<?php
session_start();
$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$warning = "";
$success = "";

// Make sure email is set
if (!isset($_SESSION['reset_email'])) {
    die("Invalid request");
}

$email = $_SESSION['reset_email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm = $_POST['confirm_password'];

    if ($password !== $confirm) {
        $warning = "Passwords do not match!";
    } else {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $conn->query("UPDATE users SET password='$hashed' WHERE email='$email'");
        unset($_SESSION['reset_email']); // clear session
        $success = "Password updated! <a href='login.php'>Login now</a>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Reset Password</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-page">
  <div class="right-side">
    <div class="login-card">
      <h1>RESET PASSWORD</h1>

      <?php if($warning) echo "<div class='warning'>$warning</div>"; ?>
      <?php if($success) echo "<div class='success'>$success</div>"; ?>

      <form method="POST" action="">
        <div class="input-group">
          <input type="password" name="password" placeholder="NEW PASSWORD" required>
        </div>
        <div class="input-group">
          <input type="password" name="confirm_password" placeholder="CONFIRM PASSWORD" required>
        </div>
        <button type="submit" class="btn primary">UPDATE PASSWORD</button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
