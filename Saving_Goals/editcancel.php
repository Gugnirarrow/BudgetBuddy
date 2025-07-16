<?php
require "../db.php";
session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}

$button = $_POST['click'];
$goalid = $_POST['goalid'];
$goalname = $_POST['goalname'];
$target = $_POST['target'];
$saved  = $_POST['saved'];
$date   = $_POST['transdate'];
$stmt = "select name from goals where goal_id='{$goalid}'";
$name = mysqli_fetch_assoc(mysqli_query($conn,$stmt));

if($button == 1){
    header("Location: ./");
}
elseif($button == 0){
    $stmt = "update goals set name='{$goalname}',target_amount='{$target}',saved_amount='{$saved}',deadline='{$date}' where goal_id='{$goalid}'";
    if(mysqli_query($conn,$stmt)){
        echo"
        <script>
            alert('Goal updated!');
            window.location.href='./';
        </script>
        ";
    }
}
?>