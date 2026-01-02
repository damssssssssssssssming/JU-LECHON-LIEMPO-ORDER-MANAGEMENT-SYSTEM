<?php
$conn = new mysqli("localhost", "root", "", "posa");
$settings = $conn->query("SELECT * FROM store_settings LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($settings['store_name']) ?></title>
    <link rel="stylesheet" href="landing page.css">
</head>
<body>

<section class="hero" style="background-image:url('uploads/<?= htmlspecialchars($settings['dashboard_image']) ?>')">
    <div class="overlay">
        <h1>WELCOME!</h1>
        <h2><?= htmlspecialchars($settings['store_name']) ?><br>ORDER MANAGEMENT SYSTEM</h2>

        <h5 class="tag">
            Enjoy delicious lechon manok and liempo at prices that fit your budget—perfect for everyday cravings and family meals.
        </h5>

        <a href="login.php" class="btn">ORDER NOW</a>
    </div>

    <div class="info-overlay">
        <p>📞 <?= htmlspecialchars($settings['contact_number']) ?></p>
        <p>📍 <?= nl2br(htmlspecialchars($settings['address'])) ?></p>
    </div>
</section>

</body>
</html>
