<?php
session_start();
require_once 'db.php';

// Check login
if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$edit_mode = false;
$transaction = [
    'transaction_id' => '',
    'category_id' => '',
    'amount' => '',
    'description' => '',
    'transaction_date' => ''
];

// If edit mode
if(isset($_GET['id'])){
    $edit_mode = true;
    $transaction_id = $_GET['id'];

    $sql = "SELECT * FROM transactions WHERE transaction_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $transaction_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows == 1){
        $transaction = $result->fetch_assoc();
    } else {
        $_SESSION['error'] = "Transaction not found.";
        header("Location: transactions.php");
        exit();
    }
}

// Fetch categories for dropdown
$categories = [];
$sql = "SELECT * FROM categories ORDER BY type, name";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()){
    $categories[] = $row;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $edit_mode ? "Edit" : "Add"; ?> Transaction - BudgetBuddy</title>
</head>
<body>
    <h2><?php echo $edit_mode ? "Edit" : "Add"; ?> Transaction</h2>

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

    <form action="transaction_process.php" method="POST">
        <input type="hidden" name="transaction_id" value="<?php echo $transaction['transaction_id']; ?>">

        <label>Category:</label><br>
        <select name="category_id" required>
            <option value="">Select category</option>
            <?php foreach($categories as $cat): ?>
                <option value="<?php echo $cat['category_id']; ?>"
                    <?php if($cat['category_id'] == $transaction['category_id']) echo "selected"; ?>>
                    <?php echo htmlspecialchars($cat['name']." (".$cat['type'].")"); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Amount:</label><br>
        <input type="number" step="0.01" name="amount" value="<?php echo $transaction['amount']; ?>" required><br><br>

        <label>Description:</label><br>
        <input type="text" name="description" value="<?php echo htmlspecialchars($transaction['description']); ?>"><br><br>

        <label>Date:</label><br>
        <input type="date" name="transaction_date" value="<?php echo $transaction['transaction_date']; ?>" required><br><br>

        <input type="submit" value="<?php echo $edit_mode ? "Update" : "Add"; ?> Transaction">
    </form>

    <p><a href="transactions.php">Back to Transactions</a></p>
</body>
</html>
