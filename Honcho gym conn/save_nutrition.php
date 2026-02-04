<?php
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["loggedin"])) {
    $user_id = $_SESSION["id"];
    $protein = $_POST['protein'];
    $calories = $_POST['calories'];
    $today = date('Y-m-d');

    $sql = "INSERT INTO nutrition_logs (user_id, protein_grams, calories, log_date) VALUES (?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("iiis", $user_id, $protein, $calories, $today);
        $stmt->execute();
        header("location: dashboard.php");
    }
}
?>