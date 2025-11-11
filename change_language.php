<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include 'lang_helper.php';

// Check if language parameter is provided
if (isset($_GET['lang'])) {
    $lang = $_GET['lang'];
    
    if (changeLanguage($lang)) {
        // Language changed successfully
        $redirect = isset($_GET['redirect']) ? urldecode($_GET['redirect']) : 'index.php';
        header('Location: ' . $redirect);
        exit;
    }
}

// If no valid language, redirect to home
header('Location: index.php');
exit;
?>
