<?php
session_start();
require_once 'db.php';

// Check if terms checkbox is checked
if (!isset($_POST['terms'])) {
    $_SESSION['error'] = "You must agree to the Terms & Conditions.";
    header("Location: register.php");
    exit();
}

// Get POST data
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Validate passwords match
if ($password !== $confirm_password) {
    $_SESSION['error'] = "Passwords do not match.";
    header("Location: register.php");
    exit();
}

// Check if email already exists
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['error'] = "Email already registered.";
    header("Location: register.php");
    exit();
}

// Hash password
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Insert user into database
$sql = "INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $hashed_password);

if ($stmt->execute()) {
    $_SESSION['success'] = "Registration successful. Please login.";
    header("Location: register.php");
    exit();
} else {
    $_SESSION['error'] = "Error: " . $conn->error;
    header("Location: register.php");
    exit();
}

$conn->close();
?>
