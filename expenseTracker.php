<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 🔷 Fetch user data
$sql = "SELECT * FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

// 🔷 Fetch transactions
$sql = "SELECT t.*, c.type AS category_type, c.name AS category_name 
        FROM transactions t 
        JOIN categories c ON t.category_id = c.category_id
        WHERE t.user_id = ?
        ORDER BY t.transaction_date DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$transactions = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// 🔷 Calculate total balance
$total_balance = 0;
foreach ($transactions as $t) {
    if ($t['category_type'] == 'income') $total_balance += $t['amount'];
    else $total_balance -= $t['amount'];
}

// 🔷 Fetch aggregated data
function fetch_aggregated($conn, $user_id, $type, $group) {
    if ($group == 'daily') {
        $group_by = "DATE(t.transaction_date)";
        $label = "DATE(t.transaction_date) AS label";
    } elseif ($group == 'weekly') {
        $group_by = "YEARWEEK(t.transaction_date)";
        $label = "YEARWEEK(t.transaction_date) AS label";
    } else { // monthly
        $group_by = "DATE_FORMAT(t.transaction_date, '%Y-%m')";
        $label = "DATE_FORMAT(t.transaction_date, '%Y-%m') AS label";
    }

    $sql = "SELECT $label, SUM(t.amount) as total 
            FROM transactions t 
            JOIN categories c ON t.category_id = c.category_id
            WHERE t.user_id = ? AND c.type = ?
            GROUP BY $group_by
            ORDER BY t.transaction_date";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $user_id, $type);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

$daily_income = fetch_aggregated($conn, $user_id, 'income', 'daily');
$weekly_income = fetch_aggregated($conn, $user_id, 'income', 'weekly');
$monthly_income = fetch_aggregated($conn, $user_id, 'income', 'monthly');

$daily_expense = fetch_aggregated($conn, $user_id, 'expense', 'daily');
$weekly_expense = fetch_aggregated($conn, $user_id, 'expense', 'weekly');
$monthly_expense = fetch_aggregated($conn, $user_id, 'expense', 'monthly');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Expense Tracker</title>
  <link rel="stylesheet" href="expenseTrackerStyle.css"/>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    .tracker-body {
      display: flex;
      align-items: flex-start;
      gap: 40px;
    }
    .chart-wrapper {
      flex: 0 0 400px;
    }
    .item-list {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }
  </style>
</head>
<body>
<div class="app-layout">
  <!-- Sidebar -->
  <aside class="sidebar">
    <div class="logo">
      <img src="img/Group 43.png" alt="BudgetBuddy Logo">
    </div>
    <nav class="navigation">
      <ul>
        <li><a href="homepage.php" class="nav-item">Home page</a></li>
        <li><a href="budgetplanner.php" class="nav-item">Budget Planner</a></li>
        <li><a href="expenseTracker.php" class="nav-item active">Expense tracker</a></li>
        <li><a href="Financial_Summary/" class="nav-item">Financial Health Summary</a></li>
        <li><a href="Smart_Suggestion/" class="nav-item">Smart Suggestion</a></li>
        <li><a href="Saving_Goals/" class="nav-item">Saving Goals</a></li>
      </ul>
    </nav>
    <div class="add-button">
      <a href="inputTransactionPage.php"><img src="img/Vector.png" alt="Add"></a>
    </div>
  </aside>

  <!-- Main content -->
  <div class="content-area">
    <main class="main-content">
      <div class="top-bar">
        <h2>Expense Tracker</h2>
      </div>

      <!-- ✅ Total Balance box -->
      <div class="income-box">
        <div class="box-label">Total Balance</div>
        <div id="balanceDisplay" class="box-amount">RM <?php echo number_format($total_balance, 2); ?></div>
      </div>

      <div class="tracker-body">
        <!-- Graph section -->
        <div class="chart-wrapper">
          <canvas id="lineChart" height="200"></canvas>
          <div class="toggle-group">
            <button id="toggleIncome" class="toggle active">Income</button>
            <button id="toggleExpense" class="toggle">Expense</button>
          </div>
          <div class="toggle-group">
            <button class="toggle period-btn active" data-period="daily">Daily</button>
            <button class="toggle period-btn" data-period="weekly">Weekly</button>
            <button class="toggle period-btn" data-period="monthly">Monthly</button>
          </div>
        </div>

        <!-- Transaction lists -->
        <div id="transactionList" class="item-list">
          <!-- Filled dynamically -->
        </div>
      </div>

    </main>
  </div>
</div>

<script>
const dailyIncome = <?php echo json_encode($daily_income); ?>;
const weeklyIncome = <?php echo json_encode($weekly_income); ?>;
const monthlyIncome = <?php echo json_encode($monthly_income); ?>;
const dailyExpense = <?php echo json_encode($daily_expense); ?>;
const weeklyExpense = <?php echo json_encode($weekly_expense); ?>;
const monthlyExpense = <?php echo json_encode($monthly_expense); ?>;
const transactions = <?php echo json_encode($transactions); ?>;

let currentType = 'income';
let currentPeriod = 'daily';

const ctx = document.getElementById('lineChart').getContext('2d');
let chart = new Chart(ctx, {
  type: 'line',
  data: { labels: [], datasets: [{ label: '', data: [], borderColor: '', tension: 0.3 }] },
  options: { responsive: true, plugins: { legend: { display: true } }, scales: { y: { beginAtZero: true } } }
});

function updateChart() {
  let dataSet;
  if (currentType === 'income') {
    if (currentPeriod === 'daily') dataSet = dailyIncome;
    else if (currentPeriod === 'weekly') dataSet = weeklyIncome;
    else dataSet = monthlyIncome;
  } else {
    if (currentPeriod === 'daily') dataSet = dailyExpense;
    else if (currentPeriod === 'weekly') dataSet = weeklyExpense;
    else dataSet = monthlyExpense;
  }

  const labels = dataSet.map(row => row.label);
  const data = dataSet.map(row => parseFloat(row.total));

  chart.data.labels = labels;
  chart.data.datasets[0].data = data;
  chart.data.datasets[0].label = currentType.charAt(0).toUpperCase() + currentType.slice(1);
  chart.data.datasets[0].borderColor = (currentType === 'income') ? '#4cff78' : '#ff4d4f';
  chart.update();
}

function updateList() {
  const list = document.getElementById('transactionList');
  list.innerHTML = '';

  transactions.filter(t => t.category_type === currentType).forEach(t => {
    const item = document.createElement('div');
    item.className = 'item-card';
    item.innerHTML = `
      <div class="icon">${currentType === 'income' ? '💰' : '💸'}</div>
      <div class="info">
        <div class="title">${t.category_name}</div>
        <div class="sub">${t.description}</div>
      </div>
      <div class="amount ${currentType}">${currentType === 'income' ? '+' : '-'}RM ${parseFloat(t.amount).toFixed(2)}</div>
      <div class="time">${t.transaction_date}</div>
    `;
    list.appendChild(item);
  });
}

document.getElementById('toggleIncome').addEventListener('click', () => {
  currentType = 'income';
  document.getElementById('toggleIncome').classList.add('active');
  document.getElementById('toggleExpense').classList.remove('active');
  updateChart();
  updateList();
});

document.getElementById('toggleExpense').addEventListener('click', () => {
  currentType = 'expense';
  document.getElementById('toggleExpense').classList.add('active');
  document.getElementById('toggleIncome').classList.remove('active');
  updateChart();
  updateList();
});

document.querySelectorAll('.period-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.period-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    currentPeriod = btn.dataset.period;
    updateChart();
  });
});

// Initial load
updateChart();
updateList();
</script>

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
