<?php
$progress = isset($_GET['progress']) ? (int)$_GET['progress'] : 0;
$nextProgress = $progress < 100 ? $progress + 10 : 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulated Progress Bar</title>
    <style>
        .progress-container {
            width: 100%;
            background-color: #f3f3f3;
            border: 1px solid #ccc;
            border-radius: 5px;
            overflow: hidden;
        }
        .progress-bar {
            height: 20px;
            width: <?php echo $progress; ?>%;
            background-color: #2196F3;
            text-align: center;
            color: white;
            line-height: 20px;
        }
    </style>
</head>
<body>
    <div class="progress-container">
        <div class="progress-bar"><?php echo $progress; ?>%</div>
    </div>
    <form method="get">
        <input type="hidden" name="progress" value="<?php echo $nextProgress; ?>">
        <button type="submit">Update Progress</button>
    </form>
</body>
</html>
