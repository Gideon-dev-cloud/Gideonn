<?php
session_start();
require_once 'db_config.php';

// Check if user is logged in and an ID was sent
if (isset($_SESSION["loggedin"]) && isset($_GET['id'])) {
    $progress_id = $_GET['id'];
    $user_id = $_SESSION["id"];

    // Security: Only delete if the ID belongs to the current user
    $sql = "DELETE FROM user_progress WHERE id = ? AND user_id = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ii", $progress_id, $user_id);
        
        if ($stmt->execute()) {
            // Success: Redirect back to progress page
            header("location: progress.php?deleted=true");
        } else {
            echo "Error deleting record.";
        }
        $stmt->close();
    }
} else {
    header("location: progress.php");
}
$conn->close();
?>