<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["is_admin"] != 1) { exit("Unauthorized"); }

if (isset($_GET['id']) && isset($_GET['status'])) {
    $order_id = (int)$_GET['id'];
    $new_status = $_GET['status']; // e.g., 'Shipped' or 'Completed'

    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $order_id);
    
    if ($stmt->execute()) {
        header("location: admin_dashboard.php?msg=updated");
    }
}
?>