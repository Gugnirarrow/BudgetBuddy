<?php
session_start();
require_once 'db.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

### DELETE transaction
if(isset($_GET['delete_id'])){
    $transaction_id = $_GET['delete_id'];

    $sql = "DELETE FROM transactions WHERE transaction_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $transaction_id, $user_id);

    if($stmt->execute()){
        $_SESSION['success'] = "Transaction deleted successfully.";
    } else {
        $_SESSION['error'] = "Error deleting transaction.";
    }

    header("Location: transactions.php");
    exit();
}

###  INSERT / UPDATE transaction
if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $transaction_id = $_POST['transaction_id'];
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    $transaction_date = $_POST['transaction_date'];

    if(empty($category_id) || empty($amount) || empty($transaction_date)){
        $_SESSION['error'] = "Please fill in all required fields.";
        header("Location: transaction_form.php");
        exit();
    }

    if($transaction_id){ // Update
        $sql = "UPDATE transactions SET category_id=?, amount=?, description=?, transaction_date=? WHERE transaction_id=? AND user_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("idssii", $category_id, $amount, $description, $transaction_date, $transaction_id, $user_id);
        if($stmt->execute()){
            $_SESSION['success'] = "Transaction updated successfully.";
        } else {
            $_SESSION['error'] = "Error updating transaction.";
        }
    } else { // Insert
        $sql = "INSERT INTO transactions (user_id, category_id, amount, description, transaction_date, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidss", $user_id, $category_id, $amount, $description, $transaction_date);
        if($stmt->execute()){
            $_SESSION['success'] = "Transaction added successfully.";
        } else {
            $_SESSION['error'] = "Error adding transaction.";
        }
    }

    header("Location: expenseTracker.php");
    exit();
}
?>
