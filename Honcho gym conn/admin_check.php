<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }

// Strict role-based access control
if (!isset($_SESSION["loggedin"]) || $_SESSION["is_admin"] !== 1) {
    header("location: login.php?error=unauthorized");
    exit;
}
?>