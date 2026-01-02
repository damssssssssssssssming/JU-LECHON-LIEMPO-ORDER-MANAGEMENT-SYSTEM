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
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['role']     = $user['role'];

            if ($user['role'] === 'Admin') {
                header("Location: adminD.php"); 
            } else {
                header("Location: dash.php"); 
            }
            exit();

        } else {
            $warning = "Incorrect password!";
        }
    } else {
        $warning = "User not found!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
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
      <h1>LOGIN</h1>

      <?php if($warning != ""): ?>
        <div class="warning"><?php echo $warning; ?></div>
      <?php endif; ?>

      <form method="POST" action="login.php">
        <div class="input-group">
          <span class="icon"><i class="fa-solid fa-user-circle"></i></span>
          <input type="text" placeholder="USERNAME" name="username" required>
        </div>

        <div class="input-group">
          <span class="icon"><i class="fa-solid fa-key"></i></span>
          <input type="password" placeholder="PASSWORD" name="password" required>
        </div>

        <a href="forgot.php" class="forgot">FORGOT PASSWORD</a>

        <button type="submit" class="btn primary">LOGIN</button>
        <a href="create.php" class="btn secondary">CREATE ACCOUNT</a>
      </form>
    </div>
  </div>
</div>

</body>
</html>
