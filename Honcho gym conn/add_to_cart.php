<?php
session_start();
require_once 'db_config.php';

// 1. Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user_id = $_SESSION["id"];
    // Ensure product_id is an integer
    $product_id = intval($_POST["product_id"]); 
    $quantity = 1; 

    // --- PHASE 1: CHECK EXISTENCE ---
    // We fetch the data and CLOSE the statement immediately to free up the connection.
    $cart_item_id = 0;
    $existing_qty = 0;
    $item_exists = false;

    $check_sql = "SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?";
    if ($stmt = $conn->prepare($check_sql)) {
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($cart_item_id, $existing_qty);
            $stmt->fetch();
            $item_exists = true;
        }
        $stmt->close(); // <--- CRITICAL: Close this before starting the next query
    } else {
        // Debugging: Failed to prepare check query
        die("Error preparing check query: " . $conn->error);
    }
    
    // --- PHASE 2: UPDATE OR INSERT ---
    if ($item_exists) {
        // Product exists, update quantity
        $new_qty = $existing_qty + 1;
        $update_sql = "UPDATE cart SET quantity = ? WHERE id = ?";
        
        if ($update_stmt = $conn->prepare($update_sql)) {
            $update_stmt->bind_param("ii", $new_qty, $cart_item_id);
            if (!$update_stmt->execute()) {
                die("Error updating cart: " . $update_stmt->error);
            }
            $update_stmt->close();
        }
    } else {
        // Product not in cart, insert new row
        $insert_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        
        if ($insert_stmt = $conn->prepare($insert_sql)) {
            $insert_stmt->bind_param("iii", $user_id, $product_id, $quantity);
            if (!$insert_stmt->execute()) {
                die("Error inserting into cart: " . $insert_stmt->error);
            }
            $insert_stmt->close();
        } else {
             die("Error preparing insert: " . $conn->error);
        }
    }
    
    // 3. Redirect back with success
    header("Location: gymproj.php?msg=Item added to cart!&type=success");
    exit;
} else {
    // If someone tries to access this file directly without POST
    header("Location: gymproj.php");
    exit;
}
?>