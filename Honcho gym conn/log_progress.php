<?php
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["loggedin"])) {
    $user_id = $_SESSION["id"];
    $exercise = $_POST['exercise_name'];
    $weight = $_POST['weight'];
    $reps = $_POST['reps'];
    $date = $_POST['date_logged'];

    $sql = "INSERT INTO user_progress (user_id, exercise_name, weight_kg, reps, date_logged) VALUES (?, ?, ?, ?, ?)";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("isdis", $user_id, $exercise, $weight, $reps, $date);
        
        if ($stmt->execute()) {
            header("location: progress.php?status=success");
        } else {
            echo "Error saving progress.";
        }
        $stmt->close();
    }
    $conn->close();
}
?>