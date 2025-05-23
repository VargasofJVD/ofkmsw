<?php
/**
 * Admin Logout Script
 * 
 * This script handles the logout process for administrators.
 * It destroys the session and redirects to the login page.
 */

// Start session if not already started
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to login page
header('Location: index.php');
exit;
?>