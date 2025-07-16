<?php
session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}

require "../db.php";
$query = "select sum(amount) as total from transactions where transaction_date = current_date 
group by category_id 
order by category_id;";
$exec = mysqli_query($conn,$query);
$data = array();
$num = 0;
$count = mysqli_num_rows($exec);

if($count>0){
    while($fetch = mysqli_fetch_assoc($exec)){
        $data[$num] = $fetch['total'];
        $num++;
    }

    $dataPoints = array( 
        array("label"=>"Entertainment", "y"=>$data[0]),
        array("label"=>"Expense", "y"=>$data[1]),
    );
}
else{
    $dataPoints = array( 
        array("label"=>"No Data", "y"=>"100"),
    );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetBuddy - Financial Health Summary</title>
    <link rel="stylesheet" href="FHSStyle.css">
    <script>
window.onload = function() {

var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	title: {
		text: "Usage Share of Desktop Browsers"
	},
	subtitles: [{
		text: "November 2017"
	}],
	data: [{
		type: "pie",
		yValueFormatString: "\"RM\"#,##0.00",
		indexLabel: "{label} ({y})",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();

}
</script>
</head>
<body>
    <div class="container">
        <aside class="sidebar">
            <div class="logo">
                <img src="Image/Group 43.png" alt="BudgetBuddy Logo">
            </div>
            <nav class="navigation">
                <ul>
                    <li><a href="../homepage.php" class="nav-item">Home page</a></li>
                    <li><a href="../budgetPlanner.php" class="nav-item">Budget Planner</a></li>
                    <li><a href="../expenseTracker.php" class="nav-item">Expense tracker</a></li>
                    <li><a href="./" class="nav-item active">Financial Health Summary</a></li>
                    <li><a href="../Smart_Suggestion/" class="nav-item">Smart Suggestion</a></li>
                    <li><a href="../Saving_Goals/" class="nav-item">Saving Goals</a></li>
                </ul>
            </nav>
            <div class="add-button">
                <a href="../inputTransactionPage.php"><img src="Image/Vector.png" alt="Add"></a>
            </div>
                    </aside>

        <main class="main-content">

            <header class="header">
            </header>

            <section class="financial-summary-section">
                <h2>Your Financial Snapshot</h2>
                <div class="summary-content">
                    <div class="remaining-budget">
                        <h3>Remaining Budget</h3>
                        <p class="amount">RM 500</p>
                    </div>
                    <div class="overspending-alerts">
                        <h3>Overspending Alerts</h3>
                        <ul>
                            <li><span class="alert-indicator red"></span> Food</li>
                            <li><span class="alert-indicator yellow"></span> Transport</li>
                            <li><span class="alert-indicator green"></span> Entertainment</li>
                        </ul>
                    </div>
                </div>
                <p class="budget-spent-info">You have spent 80% of your food budget this week.</p>

                <div class="chart-area">
  
                        <div id="chartContainer" style="height: 370px; width: 100%;"></div>

                    <div class="time-filters">
                        <button class="filter-button active">Daily</button>
                        <button class="filter-button">Weekly</button>
                        <button class="filter-button">Monthly</button>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="email-subscribe">
                <p>Want to know more?</p>
                <p>Subscribe to our mail and receive updates on our courses!</p>
                <input type="email" placeholder="Email address">
            </div>
            <div class="footer-logo">
                <img src="Image/Group 43.png" alt="BudgetBuddy Logo">
            </div>
            <p class="copyright">Copyright © 2025 Budget Buddy Company. All rights reserved.</p>
        </div>
    </footer>
    <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
    <script src="FHS.js"></script>
</body>
</html>