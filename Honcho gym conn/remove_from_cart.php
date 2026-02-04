<?php
session_start();
require_once 'db_config.php';

if (isset($_GET['id']) && isset($_SESSION['id'])) {
    $product_id = intval($_GET['id']);
    $user_id = $_SESSION['id'];

    // Ensure we only delete the specific product for the specific user
    $sql = "DELETE FROM cart WHERE product_id = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $product_id, $user_id);
    
    if ($stmt->execute()) {
        header("location: cart.php?status=removed");
    } else {
        header("location: cart.php?status=error");
    }
} else {
    header("location: cart.php");
}
exit();