<?php
session_start();
$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) die("DB Connection failed");

$result = $conn->query("SELECT * FROM products WHERE status='available'");
$products = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order | Menu</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link rel="stylesheet" href="dash.css">
  <link rel="stylesheet" href="order.css">
</head>
<body>

<div class="topbar">
  <button class="ghost-btn menu-icon" onclick="toggleSidebar()">
    <i class="fa-solid fa-bars"></i>
  </button>
</div>

<div class="layout">

  <div class="sidebar" id="sidebar">
    <a href="dash.php"><i class="fa-solid fa-house"></i> Home</a>
    <a href="order.php"><i class="fa-solid fa-list"></i> Orders</a>
    <a href="status.php"><i class="fa-solid fa-clock"></i> Pending Orders</a>
    <a href="report.php"><i class="fa-solid fa-chart-bar"></i> Reports</a>
  </div>

  <div class="menu-header">
    <h2>Menu</h2>
    <p>Choose your item and quantity</p>
  </div>

  <div class="content-box">
    <div class="menu-grid">

      <?php foreach ($products as $p): ?>
      <div class="menu-card"
           data-id="<?= $p['id'] ?>"
           data-stock="<?= (int)$p['stock'] ?>"
           data-price="<?= $p['price'] ?>">

        <?php if ($p['stock'] > 0): ?>
          <span class="stock-badge <?= $p['stock'] <= 10 ? 'low' : '' ?>">
            <?= $p['stock'] ?> left
          </span>
        <?php else: ?>
          <span class="stock-badge low">Out of stock</span>
        <?php endif; ?>

        <img src="<?= $p['image'] ?>" alt="<?= htmlspecialchars($p['name']) ?>">

        <h3><?= htmlspecialchars($p['name']) ?></h3>
        <p class="desc"><?= htmlspecialchars($p['description']) ?></p>
        <div class="price">₱<?= number_format($p['price'], 2) ?></div>

        <div class="qty-box">
          <button class="qty-btn plusminus"
                  onclick="changeQty(this,-1)"
                  <?= $p['stock'] == 0 ? 'disabled' : '' ?>>−</button>

          <span class="qty">0</span>

          <button class="qty-btn plusminus"
                  onclick="changeQty(this,1)"
                  <?= $p['stock'] == 0 ? 'disabled' : '' ?>>+</button>
        </div>

        <button class="order-btn"
                <?= $p['stock'] == 0 ? 'disabled style="opacity:.5;cursor:not-allowed;"' : '' ?>>
          <?= $p['stock'] == 0 ? 'OUT OF STOCK' : 'ADD TO ORDER' ?>
        </button>

      </div>
      <?php endforeach; ?>

    </div>
  </div>
</div>

<div id="floating-cart" class="floating-cart hidden">
  <div class="cart-left">
    <i class="fa-solid fa-cart-shopping"></i>
    <span id="cart-count">0 items</span>
  </div>
  <div class="cart-right">
    <span id="cart-total">₱0</span>
    <button class="view-cart-btn">VIEW CART</button>
  </div>
</div>

<div id="cart-modal" class="cart-modal hidden">
  <div class="cart-box">
    <h3>Your Order</h3>
    <div id="cart-items"></div>
    <div class="cart-footer">
      <strong>Total: <span id="modal-total">₱0</span></strong>
      <button id="checkout-btn">Checkout</button>
      <button id="close-cart">Close</button>
    </div>
  </div>
</div>

<script src="sidebar.js"></script>
<script src="order.js"></script>

</body>
</html>
