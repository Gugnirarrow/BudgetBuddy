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
        $_SESSION['email'] = $row['email'];
        echo "<script>alert('You have been logged in successfully.');
            self.location.href='dashboard.php';
            </script>";
        exit();
    } else {
        echo "<script>alert('Your password is wrong.');
            self.location.href='login.php';
            </script>";
        exit();
    }
} else {
        echo "<script>alert('No account found with that email.');
                self.location.href='login.php';
                </script>";
    exit();
}

$conn->close();
?>
