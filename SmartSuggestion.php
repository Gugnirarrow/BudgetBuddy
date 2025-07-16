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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetBuddy - Smart Suggestion</title>
    <link rel="stylesheet" href="SmartSuggestionStyle.css">
</head>
<body>
    <div class="container">
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
                  <li><a href="SmartSuggestion.php" class="nav-item active">Smart Suggestion</a></li>
                  <li><a href="SavingGoals.html" class="nav-item">Saving Goals</a></li>
                </ul>
            </nav>
            <div class="add-button">
                <a href="inputTransactionPage.php"><img src="img/Vector.png" alt="Add"></a>
            </div>
        </aside>

        <main class="main-content">
            <header class="header">
            </header>

            <section class="smart-suggestion-section">
                <h2>Smart Tips For Your Budget!</h2>
                <div class="tip-cards">
                    <div class="card">
                        <img src="img/directions_bus.png" alt="Bus icon">
                        <h3>Transportation Tips</h3>
                        <p>Use student transport cards to reduce costs.</p>
                    </div>
                    <div class="card">
                        <img src="img/percent.png" alt="Percent icon">
                        <h3>Campus Deals & Events</h3>
                        <p>Suggest free university events or campus deals.</p>
                    </div>
                    <div class="card">
                        <img src="img/Shopping bag.png" alt="Shopping bag icon">
                        <h3>Cheaper Alternatives</h3>
                        <p>Recommend a cheaper alternatives for recurring expenses (e.g., specific eateries, study materials).</p>
                    </div>
                </div>
                <div class="box">
                    <p class="suggestion">*Note on Data Limitation:</p>
                    <p class="suggestion-detail">Suggestions are based on information within Taylor's University campus.</p>
                </div>
                
            </section>
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
            <p class="copyright">Copyright © 2025 Budget Buddy Company. All rights reserved.</p>
        </div>
    </footer>

    <script src="SS.js"></script>
</body>
</html>