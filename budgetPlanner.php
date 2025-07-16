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
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Budget Planner</title>
  <link rel="stylesheet" href="styleBudgetPlanner.css"/>
</head>
<body>

  <!-- 整个 App 左右布局 -->
  <div class="app-layout">

    <aside class="sidebar">
            <div class="logo">
                <img src="img/Group 43.png" alt="BudgetBuddy Logo">
            </div>
            <nav class="navigation">
                <ul>
                  <li><a href="homepage.php" class="nav-item">Home page</a></li>
                  <li><a href="budgetplanner.php" class="nav-item active">Budget Planner</a></li>
                  <li><a href="expenseTracker.php" class="nav-item">Expense tracker</a></li>
                  <li><a href="Financial_Summary/" class="nav-item">Financial Health Summary</a></li>
                  <li><a href="Smart_Suggestion/" class="nav-item">Smart Suggestion</a></li>
                  <li><a href="Saving_Goals/" class="nav-item">Saving Goals</a></li>
              </ul>
            </nav>
            <div class="add-button">
                <a href="inputTransactionPage.php"><img src="img/Vector.png" alt="Add"></a>
            </div>
        </aside>

    <!-- 右侧主内容 -->
    <div class="content-area">
      <main class="main-content">

        <!-- 顶部返回和标题 -->
        <div class="top-bar">
          <span class="back-arrow">←</span>
          <h2>Monthly Income</h2>
        </div>

        <!-- 可编辑的收入显示框 -->
        <div class="income-box">
          <input id="incomeInput" type="number" min="0" value="4000"/>
          <span class="rm">RM</span>
        </div>

        <!-- 滑块和图表 -->
        <div class="budget-body">
          <div class="sliders">
            <div class="slider-group">
              <label>Food and Rent <span id="pct1">45%</span></label>
              <input id="range1" type="range" min="0" max="100" value="45">
            </div>
            <div class="slider-group">
              <label>Savings <span id="pct2">25%</span></label>
              <input id="range2" type="range" min="0" max="100" value="25">
            </div>
            <div class="slider-group">
              <label>Others <span id="pct3">30%</span></label>
              <input id="range3" type="range" min="0" max="100" value="30">
            </div>
          </div>
          <!-- 环形图500×500 -->
          <div class="chart-area">
            <canvas id="doughnutChart" width="500" height="500"></canvas>
          </div>
        </div>
      </main>

      
    </div>
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

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    // Monthly Income 可编辑后回调
    document.getElementById('incomeInput').addEventListener('input', e => {
      const income = +e.target.value || 0;
      console.log('New monthly income:', income);
      // TODO: 如果需要，可以把 income 用来重新计算滑块或图表
    });

    // 原有滑块 + 环图逻辑
    const colors = ['#01005D','#B6B6FA','#2351D8'];
    const sliders = ['range1','range2','range3'].map(id=>document.getElementById(id));
    const labels  = ['pct1','pct2','pct3'].map(id=>document.getElementById(id));

    function updateGradient(slider,color){
      const v = slider.value;
      slider.style.background =
        `linear-gradient(to right, ${color} 0%, ${color} ${v}%, #fff ${v}%, #fff 100%)`;
    }

    function redistribute(idx){
      const vals   = sliders.map(s=>+s.value);
      const remain = 100 - vals[idx];
      const others = [0,1,2].filter(i=>i!==idx);
      const sum    = vals[others[0]] + vals[others[1]] || 1;
      sliders[others[0]].value = vals[others[0]]/sum * remain;
      sliders[others[1]].value = vals[others[1]]/sum * remain;
      sliders.forEach((s,i)=> labels[i].innerText = Math.round(s.value)+'%');
    }

    const ctx = document.getElementById('doughnutChart').getContext('2d');
    const chart = new Chart(ctx, {
      type:'doughnut',
      data:{
        labels:['Food and Rent','Savings','Others'],
        datasets:[{ data: sliders.map(s=>+s.value), backgroundColor:colors }]
      },
      options:{
        responsive:false,
        maintainAspectRatio:false,
        cutout:'60%',
        plugins:{ legend:{ display:false } }
      }
    });

    sliders.forEach((s,i)=>{
      updateGradient(s,colors[i]);
      labels[i].innerText = s.value+'%';
      s.addEventListener('input',()=>{
        redistribute(i);
        sliders.forEach((x,j)=>updateGradient(x,colors[j]));
        chart.data.datasets[0].data = sliders.map(x=>+x.value);
        chart.update();
      });
    });
  </script>
</body>
</html>
