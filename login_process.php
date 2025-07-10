<?php
session_start();
require_once 'db.php';

// Get POST data
$email = trim($_POST['email']);
$password = $_POST['password'];

// Check if email exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    // Verify password
    if (password_verify($password, $row['password'])) {
        // Password correct, set session variables
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        header("Location: dashboard.php"); // Redirect to dashboard or homepage
        exit();
    } else {
        $_SESSION['error'] = "Incorrect password.";
        header("Location: login.php");
        exit();
    }
} else {
    $_SESSION['error'] = "No account found with that email.";
    header("Location: login.php");
    exit();
}

$conn->close();
?>
