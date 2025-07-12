<?php
session_start();
session_unset(); // Unset all session variables
session_destroy();
echo "<script>alert('You have been logged out successfully.');
location.replace('login.php');
</script>";
//header("Location: login.php");
//exit();
?>

