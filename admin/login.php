<?php
session_start();

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

// Redirect to main login page
header('Location: ../login.php');
exit;
?>