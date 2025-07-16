<?php
require "../db.php";
session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BudgetBuddy - Saving Goals</title>
    <link rel="stylesheet" href="SavingGoalStyle.css">
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
                    <li><a href="../Financial_Summary/" class="nav-item">Financial Health Summary</a></li>
                    <li><a href="../Smart_Suggestion/" class="nav-item">Smart Suggestion</a></li>
                    <li><a href="./" class="nav-item active">Saving Goals</a></li>
                </ul>
            </nav>
            
            <div class="add-button">
                <a href="../inputTransactionPage.php"><img src="Image/Vector.png" alt="Add"></a>
            </div>
        <main class="main-content">
            </aside>
            <header class="header">
            </header>

            <section class="saving-goals-section">
                <h2>Your Saving Targets</h2>
                <p class="current-goals">Current Goals</p>
                <a href="add_form.php"><button class="add-goals-button">Add Goals</button></a>

                
                <div class="goal-cards">
                    <?php
                        $stmt = "select * from goals";
                        $exec = mysqli_query($conn,$stmt);
                        $num = 1;
                        while($rows = mysqli_fetch_assoc($exec)){
                            $target = $rows['target_amount'];
                            $saved  = $rows['saved_amount'];
                            $progress = ceil($saved/$target*100);
                            echo "
                            <form action='process.php' method='post'>
                            <div class='goal-card'>
                                <input type='hidden' id='goalid' name='goalid' value='{$rows['goal_id']}'/>
                                <p class='card-number'>Goal Card $num<p>
                                <h3> {$rows['name']} </h3>
                                <p>Target Amount: RM {$target} </p>
                                <p>Target Date: By {$rows['deadline']} </p>
                                <p>Saved So Far: RM {$saved} / RM {$target}</p>
                                <p>Progress Bar:</p>
                                <div class='progress-bar-container'>
                                    <div class='progress-bar' style='width: {$progress}%;'>{$progress}%</div>
                                </div>
                                <div class='card-actions'>
                                    <button name='click' value='0' class='edit-button'>Edit</button>
                                    <button name='click' value='1' class='delete-button'>Delete</button>
                                </div>
                            </div>
                            </form>";
                            
                            $num++;
                        }
                    ?>

                    <!-- <div class="goal-card">
                        <p class="card-number">Goal Card 3</p>
                        <h3>Enter a goal</h3>
                        <p>Target Amount:</p>
                        <input type="text" class="input-field" placeholder="|">
                        <p>Target Date:</p>
                        <input type="text" class="input-field">
                        <p>Saved So Far:</p>
                        <p class="saved-so-far-input">RM <span class="saved-percentage">0%</span></p>
                        <p>Progress Bar:</p>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: 0%;">0%</div>
                        </div>
                        <div class="card-actions">
                            <button id='click' value='0' class="edit-button">Edit</button>
                            <button id='click' value='1' class="delete-button">Delete</button>
                        </div>
                    </div> -->
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
    <script src="SG.js"></script>
</body>
</html>