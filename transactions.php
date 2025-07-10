<?php
session_start();
require_once 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT t.*, c.name AS category_name, c.type AS category_type
        FROM transactions t
        JOIN categories c ON t.category_id = c.category_id
        WHERE t.user_id = ?
        ORDER BY t.transaction_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transactions - BudgetBuddy</title>
</head>
<body>
    <h2>Your Transactions</h2>

    <?php
    if(isset($_SESSION['error'])){
        echo "<p style='color:red;'>".$_SESSION['error']."</p>";
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['success'])){
        echo "<p style='color:green;'>".$_SESSION['success']."</p>";
        unset($_SESSION['success']);
    }
    ?>

    <table border="1" cellpadding="5" cellspacing="0">
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Type</th>
            <th>Amount</th>
            <th>Description</th>
            <th>Actions</th>
        </tr>
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['transaction_date']; ?></td>
            <td><?php echo htmlspecialchars($row['category_name']); ?></td>
            <td><?php echo $row['category_type']; ?></td>
            <td>RM <?php echo number_format($row['amount'],2); ?></td>
            <td><?php echo htmlspecialchars($row['description']); ?></td>
            <td>
                <a href="transaction_form.php?id=<?php echo $row['transaction_id']; ?>">Edit</a>
                |
                <a href="transaction_process.php?delete_id=<?php echo $row['transaction_id']; ?>" onclick="return confirm('Are you sure you want to delete this transaction?');">Delete</a>
            </td>

        </tr>
        <?php endwhile; ?>
    </table>
    <a href="transaction_form.php">Add New Transaction</a><br><br>
</body>
</html>
