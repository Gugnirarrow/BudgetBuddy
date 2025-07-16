<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 여기서 $user_id를 사용하여 사용자 데이터 불러오기
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

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


<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Input Transaction Page</title>
<link href="inputTransactionPageStyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="layout">
	
<aside class="sidebar">
            <div class="logo">
                <img src="img/Group 43.png" alt="BudgetBuddy Logo">
            </div>
            <nav class="navigation">
                <ul>
                  <li><a href="homepage.php" class="nav-item">Home page</a></li>
                  <li><a href="budgetplanner.php" class="nav-item">Budget Planner</a></li>
                  <li><a href="expenseTracker.php" class="nav-item">Expense tracker</a></li>
                  <li><a href="FinancialHealthSummary.html" class="nav-item">Financial Health Summary</a></li>
                  <li><a href="SmartSuggestion.php" class="nav-item">Smart Suggestion</a></li>
                  <li><a href="SavingGoals.html" class="nav-item">Saving Goals</a></li>
              </ul>
            </nav>
            <div class="add-button">
                <a href="inputTransactionPage.php"><img src="img/Vector.png" alt="Add"></a>
            </div>
        </aside>
	
<main class="main-content">
  <div class="input-container">
    <h2>Quick Input Transaction</h2>
    <p>Central Card</p>
	
	<div class="content-row">
    <div class="category-section">
      <label>Category:</label>
      <div class="category-grid">
        <img src="img/Group 76.png" alt="Shopping">
        <img src="img/Group 77.png" alt="Transport">
        <img src="img/Group 75.png" alt="Food">
        <img src="img/Group 64.png" alt="Study">
        <img src="img/Group 71.png" alt="Communication">
        <img src="img/Group 73.png"alt="Electronics">
        <img src="img/Group 68.png" alt="Health">
      </div>
    </div>

    <div class="form-section">
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

        <label>Amount (RM):</label><br>
        <input type="number" step="0.01" placeholder="RM" name="amount" value="<?php echo $transaction['amount']; ?>" required><br><br>

        <label>Description:</label><br>
        <input type="text" name="description" value="<?php echo htmlspecialchars($transaction['description']); ?>"><br><br>

        <label>Date:</label><br>
        <input type="date" name="transaction_date" value="<?php echo $transaction['transaction_date']; ?>" required><br><br>

        <input type="submit" value="<?php echo $edit_mode ? "Update" : "Add"; ?> Transaction">
    </form>
    </div>
  </div>
  </div>
</main>
</div>
<footer class="footer">
        <div class="footer-content">
            <div class="email-subscribe">
                <p style="font-size: 20px;"><b>Want to know more?</b></p>
                <p>Subscribe to our mail and receive updates on our courses!</p>
                <input type="email" placeholder="Email address">
            </div>
            <div class="footer-logo">
                <img src="img/Group 43.png" alt="BudgetBuddy Logo">
            </div>
            <p class="copyright">Copyright ©️ 2025 Budget Buddy Company. All rights reserved.</p>
        </div>
 </footer>

</body>
</html>
