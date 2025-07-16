<?php
require "../db.php";
session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}
echo "
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>BudgetBuddy - Saving Goals</title>
        <link rel='stylesheet' href='SavingGoalStyle.css'>
    </head>
    <body>
    <div class='container''>
        <aside class='sidebar'>
            <div class='logo'>
                <img src='Image/Group 43.png' alt='BudgetBuddy Logo'>
            </div>
            <nav class='navigation'>
                <ul>
                    <li><a href='#' class='nav-item'>Home page</a></li>
                    <li><a href='#' class='nav-item'>Budget Planner</a></li>
                    <li><a href='#' class='nav-item'>Expense tracker</a></li>
                    <li><a href='../Financial_Summary/' class='nav-item'>Financial Health Summary</a></li>
                    <li><a href='../Smart_Suggestion/' class='nav-item'>Smart Suggestion</a></li>
                    <li><a href='./' class='nav-item active'>Saving Goals</a></li>
                </ul>
            </nav>
            <div class='add-button'>
                <img src='Image/Vector.png' alt='Add'>
            </div>
        <main class='main-content'>
            </aside>
            <header class='header'>
            </header>

            <section class='saving-goals-section'>
                <h2>Your Saving Targets</h2>
                <p class='current-goals'>Current Goals</p>

                
                <div class='goal-cards'>
                    <form action='add_process.php' method='post'
                    <div class='goal-card'>
                        <h3>Add Goal</h3>
                        <p>Goal Name:</p>
                        <input type='text' name='goalname' class='input-field'>
                        <p>Target Amount:</p>
                        <input type='text' name='target' class='input-field'>
                        <p>Saved Amount:</p>
                        <input type='text' name='saved' class='input-field'>
                        <p>Target Date:</p>
                        <input type='date' name='transdate' class='input-field'>
                        <div class='card-actions'>
                            <button name='click' value='0' class='edit-button'>Add Goal</button>
                            <button name='click' value='1' class='delete-button'>Cancel</button>
                        </div>
                    </div>
                    </form>
                </div>
            </section>
        </main>
    </div>

    <footer class='footer'>
        <div class='footer-content'>
            <div class='email-subscribe'>
                <p>Want to know more?</p>
                <p>Subscribe to our mail and receive updates on our courses!</p>
                <input type='email' placeholder='Email address'>
            </div>
            <div class='footer-logo'>
                <img src='Image/Group 43.png' alt='BudgetBuddy Logo'>
            </div>
            <p class='copyright'>Copyright © 2025 Budget Buddy Company. All rights reserved.</p>
        </div>
    </footer>             

</body>'";
?>