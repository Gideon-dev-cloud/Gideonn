<?php
session_start();
require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION["loggedin"])) {
    $user_id = $_SESSION["id"];
    $address = $_POST['address'];
    $phone = $_POST['phone'];

    // 1. Fetch cart items and calculate total
    $cart_items = [];
    $grand_total = 0;
    $sql = "SELECT c.product_id, c.quantity, p.price FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $cart_items[] = $row;
        $grand_total += ($row['price'] * $row['quantity']);
    }

    if (empty($cart_items)) {
        header("location: gymproj.php");
        exit;
    }

    // 2. Insert into 'orders' table
    $order_sql = "INSERT INTO orders (user_id, total_amount, shipping_address, phone_number) VALUES (?, ?, ?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("idss", $user_id, $grand_total, $address, $phone);
    $order_stmt->execute();
    $order_id = $conn->insert_id;

    // 3. Insert items into 'order_items' table
    $item_sql = "INSERT INTO order_items (order_id, product_id, quantity, price_at_purchase) VALUES (?, ?, ?, ?)";
    $item_stmt = $conn->prepare($item_sql);
    
    foreach ($cart_items as $item) {
        $item_stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
        $item_stmt->execute();
    }

    // 4. Clear the user's cart
    $clear_sql = "DELETE FROM cart WHERE user_id = ?";
    $clear_stmt = $conn->prepare($clear_sql);
    $clear_stmt->bind_param("i", $user_id);
    $clear_stmt->execute();

    header("location: success.php?order_id=" . $order_id);
    exit;
}
?>