<?php
session_start();

// Destroy the session and clear session variables
session_unset();
session_destroy();

// Redirect to home page or login page
header("Location: index.php");
exit();
?>
