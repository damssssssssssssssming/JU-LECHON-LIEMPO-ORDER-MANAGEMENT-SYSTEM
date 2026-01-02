<?php
ob_start();
session_start();

$conn = new mysqli("localhost", "root", "", "posa");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
$warning = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];
    if ($password !== $confirm) {
        $warning = "Passwords do not match!";
    } else {
        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ? OR email = ?"
        );
        $check->bind_param("ss", $username, $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $warning = "Username or email already exists!";
        } else {

            $hashed = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $conn->prepare(
                "INSERT INTO users (username, email, password) VALUES (?, ?, ?)"
            );
            $stmt->bind_param("sss", $username, $email, $hashed);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $warning = "Registration failed. Try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Create Account</title>
  <link rel="stylesheet" href="login.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
</head>
<body>

<div class="login-page">
  <div class="left-side">
    <div class="logo-circle">
      <img src="logo.png" alt="Logo">
    </div>
  </div>

  <div class="divider"></div>

  <div class="right-side">
    <div class="login-card">
      <h1>CREATE ACCOUNT</h1>

      <?php if (!empty($warning)): ?>
        <div class="warning"><?php echo htmlspecialchars($warning); ?></div>
      <?php endif; ?>

      <form method="POST" action="create.php">
        <div class="input-group">
          <span class="icon"><i class="fa-solid fa-user-circle"></i></span>
          <input type="text" placeholder="USERNAME" name="username" required>
        </div>

        <div class="input-group">
          <span class="icon"><i class="fa-solid fa-envelope"></i></span>
          <input type="email" placeholder="EMAIL" name="email" required>
        </div>

        <div class="input-group">
          <span class="icon"><i class="fa-solid fa-key"></i></span>
          <input type="password" placeholder="PASSWORD" name="password" required>
        </div>

        <div class="input-group">
          <span class="icon"><i class="fa-solid fa-key"></i></span>
          <input type="password" placeholder="CONFIRM PASSWORD" name="confirm_password" required>
        </div>

        <button type="submit" class="btn primary">CREATE ACCOUNT</button>
        <a href="login.php" class="btn secondary">BACK TO LOGIN</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>

<?php ob_end_flush(); ?>
