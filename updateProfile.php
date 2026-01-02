<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$conn = new mysqli("localhost", "root", "", "posa");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Sanitize and get input values
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$profile_pic_path = null;

// Handle profile picture upload
if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] === UPLOAD_ERR_OK) {
    $target_dir = "uploads/";
    // Make sure the uploads folder exists, create if not
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0755, true);
    }

    // Generate unique filename to prevent overwriting
    $file_ext = strtolower(pathinfo($_FILES['profile_pic']['name'], PATHINFO_EXTENSION));
    $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($file_ext, $allowed_types)) {
        $new_filename = uniqid('profile_', true) . '.' . $file_ext;
        $target_file = $target_dir . $new_filename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            $profile_pic_path = $new_filename; // Store just filename to save in DB
        } else {
            die("Error uploading profile picture.");
        }
    } else {
        die("Invalid file type. Only JPG, JPEG, PNG & GIF allowed.");
    }
}

// Prepare SQL update statement based on inputs
if (!empty($password)) {
    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($profile_pic_path) {
        $stmt = $conn->prepare("UPDATE users SET email = ?, password = ?, profile_pic = ? WHERE id = ?");
        $stmt->bind_param("sssi", $email, $hashed_password, $profile_pic_path, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $email, $hashed_password, $user_id);
    }
} else {
    // No password change
    if ($profile_pic_path) {
        $stmt = $conn->prepare("UPDATE users SET email = ?, profile_pic = ? WHERE id = ?");
        $stmt->bind_param("ssi", $email, $profile_pic_path, $user_id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
        $stmt->bind_param("si", $email, $user_id);
    }
}

// Execute the statement and redirect back to profile page
if ($stmt->execute()) {
    header("Location: Apf.php?success=1");
    exit();
} else {
    echo "Error updating profile: " . $conn->error;
}
?>
