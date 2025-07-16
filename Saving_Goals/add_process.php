<?php
require "../db.php";
session_start();
if(!isset($_SESSION['user_id']) || !isset($_SESSION['email']) || !isset($_SESSION['name'])) {
    header('Location: login.php');
    exit();
}

$button = $_POST['click'];
$userid = $_SESSION['user_id'];
$goalname = $_POST['goalname'];
$target = $_POST['target'];
$saved  = $_POST['saved'];
$date   = $_POST['transdate'];

if($button == 1){
    header("Location: ./");
}
elseif($button == 0){
    $stmt = "INSERT INTO goals(user_id,name,target_amount,saved_amount,deadline) VALUES('{$userid}','{$goalname}','{$target}','{$saved}','{$date}')";
    echo $stmt;
    if(mysqli_query($conn,$stmt)){
        echo"
        <script>
            alert('Goal Added!');
            window.location.href='./';
        </script>
        ";
    }
}
?>