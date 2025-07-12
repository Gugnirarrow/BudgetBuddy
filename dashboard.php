<?php
session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - BudgetBuddy</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .navbar {
            background-color: #333;
            overflow: hidden;
        }
        .navbar a {
            float: left;
            display: block;
            color: #f2f2f2;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }
        .navbar a:hover {
            background-color: #555;
        }
        .navbar .right {
            float: right;
        }
    </style>
</head>
<body>

<div class="navbar">
    <a href="dashboard.php">Dashboard</a>
    <a href="transactions.php">Transactions</a>
    <a href="logout.php" class="right">Logout</a>
</div>

<div class="content">
    <h2>Welcome to BudgetBuddy Dashboard</h2>
    <p>Here you can view your financial summary and manage your budget.</p>
</div>

</body>
</html>
