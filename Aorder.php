    <?php
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
        header("Location: login.php");
        exit();
    }

    $conn = new mysqli("localhost", "root", "", "posa");
    if ($conn->connect_error) {
        die("Connection failed");
    }

    $sql = "
        SELECT 
            o.order_code,
            o.order_date,
            o.status,
            GROUP_CONCAT(oi.item_name SEPARATOR ', ') AS items
        FROM orders o
        JOIN order_items oi ON o.id = oi.order_id
        GROUP BY o.id
        ORDER BY o.order_date DESC
    ";

    $result = $conn->query($sql);
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
    <meta charset="UTF-8" />
    <title>Admin - Orders</title>

    <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>
    <link rel="stylesheet" href="adminD.css" />
    <link rel="stylesheet" href="Aorder.css" />
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
            <a href="php">Profile</a>
            <a href="login.php">Logout</a>
        </div>
        </div>
    </div>
    </div>

    <div class="sidebar" id="sidebar">
    <a href="adminD.php"><i class="fa-solid fa-house"></i> Home</a>
    <a href="Aorder.php" class="active"><i class="fa-solid fa-list"></i> Orders</a>
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
        <h2>All Orders</h2>
        <p>Complete list of all customer orders</p>
        </div>

        <div class="content-box">
        <table>
            <thead>
            <tr>
                <th>Order Number</th>
                <th>Date & Time</th>
                <th>Items Ordered</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>

            <tbody id="ordersTableBody">
            <?php if ($result->num_rows === 0): ?>
            <tr>
                <td colspan="5">No orders found</td>
            </tr>
            <?php else: while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td>#<?= htmlspecialchars($row['order_code']) ?></td>
                <td><?= date("Y-m-d h:i A", strtotime($row['order_date'])) ?></td>
                <td><?= htmlspecialchars($row['items']) ?></td>
                <td>
                <span class="status <?= strtolower($row['status']) ?>">
                    <?= htmlspecialchars($row['status']) ?>
                </span>
                </td>
                <td>
                <button class="update-btn">View</button>
                </td>
            </tr>
            <?php endwhile; endif; ?>
            </tbody>

        </table>
        </div>

    </div>
    </div>

    <script src="sidebar.js"></script>
    <script src="Aorder.js"></script>
    </body>
    </html>
