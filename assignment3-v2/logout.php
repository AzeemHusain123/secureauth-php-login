<?php
session_start();
session_unset();
session_destroy();

$_SESSION['message'] = "You have been logged out successfully!";
header("Location: login.php");
exit();
?>
