<?php
require "../db.php";
$progress = 100; // Progress percentage (0-100)

$query = "select category_id,sum(amount) as total from transactions group by category_id";
$exec = mysqli_query($conn,$query);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>PHP Progress Bar</title>
</head>
<body>
    <?php
    // while($rows = mysqli_fetch_assoc($exec)){
    //     echo $rows['total']."
    //     <div class='progress-container'>
    //         <div class='progress-bar' style='width: $progress%'> $progress%</div> 
    //     </div>";
    // }
    $fetch = mysqli_fetch_array($exec,MYSQLI_NUM);
    echo $fetch['0']."<br>";
    echo $fetch['2'];
    ?>
</body>
</html>
