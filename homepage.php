<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 사용자 기본 정보 가져오기
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// income, expense 계산
$sql = "
SELECT c.type, SUM(t.amount) as total
FROM transactions t
JOIN categories c ON t.category_id = c.category_id
WHERE t.user_id = ?
GROUP BY c.type
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$income = 0;
$expense = 0;

while ($row = $result->fetch_assoc()) {
    if ($row['type'] == 'income') {
        $income = $row['total'];
    } elseif ($row['type'] == 'expense') {
        $expense = $row['total'];
    }
}

// 최신 transactions 3개 가져오기
$sql = "
SELECT t.*, c.name as category_name, c.type as category_type
FROM transactions t
JOIN categories c ON t.category_id = c.category_id
WHERE t.user_id = ?
ORDER BY t.transaction_date DESC, t.created_at DESC
LIMIT 3
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result();
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>BudgetBuddy Dashboard</title>
<link href="homepageStyle.css" rel="stylesheet" type="text/css">
</head>

<body>
<div class="container">

  <!-- Sidebar navigation -->
  <aside class="sidebar">
    <div class="logo">
        <img src="img/Group 43.png" alt="BudgetBuddy Logo">
    </div>
    <nav class="navigation">
        <ul>
            <li><a href="homepage.php" class="nav-item active">Home page</a></li>
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

  <!-- Main content -->
  <main class="main-content">
    <!-- Greeting section -->
    <header class="greeting">
      <h2>Hi <?php echo htmlspecialchars($user['name']); ?>!</h2>
      <p>Your smart budget journey starts here.</p>
    </header>

    <!-- Total balance display -->
    <section class="balance">
      <h3>Total Balance</h3>
      <p class="amount">RM <?php echo number_format($income - $expense, 2); ?></p>
    </section>

    <!-- Income, Expense and Summary overview -->
    <section class="overview">
      <div class="card income">
         <img src="img/Income.png" alt="Income Icon" class="icon" />
        <p>Income</p>
        <strong>RM <?php echo number_format($income, 2); ?></strong>
      </div>

      <div class="card expense">
        <img src="img/Expense.png" alt="Expense Icon" class="icon" />
        <p>Expense</p> 
        <strong>RM <?php echo number_format($expense, 2); ?></strong>
      </div>

      <div class="card summary">
        <p>Summary</p>
        <div class="bar-chart">
          <img src="img/bar-chart.png" alt="Bar Chart" class="bar-chart-img" />
        </div>
        <div class="bar-labels">
          <span>1-10 Aug</span>
          <span>11-20 Aug</span>
          <span>21-30 Aug</span>
        </div>
      </div>
    </section>

    <!-- Transaction list -->
    <section class="transactions">
      <h4>Transactions</h4>

      <?php while ($row = $transactions->fetch_assoc()) : ?>
      <div class="transaction-item">
        <img src="img/<?php echo strtolower($row['category_name']); ?>.png" alt="<?php echo $row['category_name']; ?> Icon" class="icon" />
        <div>
          <span class="title"><?php echo htmlspecialchars($row['category_name']); ?></span>
          <p><?php echo htmlspecialchars($row['description']); ?></p>
        </div>
        <div class="amount">
          <?php echo ($row['category_type'] == 'expense' ? '-' : '+') . 'RM ' . number_format($row['amount'], 2); ?>
        </div>
        <div class="time"><?php echo $row['transaction_date']; ?></div>
      </div>
      <?php endwhile; ?>

    </section>
  </main>

  <!-- Right profile panel -->
  <aside class="profile-panel">
    <!-- User profile section -->
    <div class="user-profile">
      <img src="img/profile.png" alt="Profile Picture" class="profile-pic" />
      <h3><?php echo htmlspecialchars($user['name']); ?></h3>
      <p><?php echo htmlspecialchars($user['email']); ?></p>

      <button class="edit-btn">
        <img src="img/edit.png" alt="Edit Icon" class="icon" /> 
      </button>
    </div>

    <!-- Account info -->
    <div class="account-info">
      <p>Total Balance</p>
      <strong>RM <?php echo number_format($income - $expense, 2); ?></strong>
      <p>Account Status</p>
      <span class="status">Activated</span>
    </div>

    <!-- Suggestions box -->
    <div class="suggestions">
      <h4>Suggestions</h4>
      <div class="suggestion-card">
        <img src="img/transport.png" alt="Bus Icon" class="icon" />
        Use student transport cards to reduce costs.
      </div>
      <div class="suggestion-card">
        <img src="img/shopping.png" alt="Idea Icon" class="icon" />
        Recommend cheaper alternatives for recurring expenses.
      </div>
      <a class="learn-more" href="#">Learn more</a>
    </div>

    <!-- Logout button -->
    <form action="logout.php" method="POST">
      <button type="submit" class="logout-btn"> 
        <img src="img/logout.png" alt="LogOut Icon" class="logout" />Log out
      </button>
    </form>
  </aside>

</div>

<!-- Footer -->
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
    <p class="copyright">Copyright © 2025 Budget Buddy Company. All rights reserved.</p>
  </div>
</footer>
</body>
</html>
