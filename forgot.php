<?php
session_start();
$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$warning = "";
$success = ""; // fixed: define $success to avoid warning

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Check if email exists
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $_SESSION['reset_email'] = $email; // store email in session
        header("Location: reset.php"); // redirect directly to reset page
        exit();
    } else {
        $warning = "Email not found!";
    }

    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="login.css">
</head>
<body>
<div class="login-page">
  <div class="right-side">
    <div class="login-card">
      <h1>FORGOT PASSWORD</h1>

      <?php if($warning) echo "<div class='warning'>$warning</div>"; ?>
      <?php if($success) echo "<div class='success'>$success</div>"; ?>

      <form method="POST" action="">
        <div class="input-group">
          <input type="email" name="email" placeholder="EMAIL" required>
        </div>
        <button type="submit" class="btn primary">RESET PASSWORD</button>
        <a href="login.php" class="btn secondary">BACK TO LOGIN</a>
      </form>
    </div>
  </div>
</div>
</body>
</html>
