<?php
session_start();
require_once 'db_config.php';

// Check if user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders | Honcho's GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #0d0d0d;
            color: white;
            font-family: Arial, sans-serif;
        }
        .order-card {
            background-color: #1a1a1a;
            border: 1px solid #333;
            border-left: 5px solid orangered;
            border-radius: 8px;
            margin-bottom: 2rem;
            padding: 1.5rem;
        }
        .status-badge {
            background-color: orangered;
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            text-transform: uppercase;
        }
        .item-list img {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
            margin-right: 15px;
        }
        .order-header {
            border-bottom: 1px solid #333;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: orangered;"><i class="fa-solid fa-box-open me-2"></i> MY ORDER HISTORY</h2>
            <a href="dashboard.php" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
        </div>

        <?php
        // Fetch all orders for this user
        $sql = "SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $orders_result = $stmt->get_result();

            if ($orders_result->num_rows > 0) {
                while ($order = $orders_result->fetch_assoc()) {
                    $order_id = $order['id'];
                    ?>
                    <div class="order-card">
                        <div class="order-header d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">Order #<?php echo $order_id; ?></h5>
                                <small class="text-secondary">Placed on: <?php echo date('F j, Y', strtotime($order['created_at'])); ?></small>
                            </div>
                            <span class="status-badge"><?php echo $order['status']; ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-secondary mb-3">Items:</h6>
                                <div class="item-list">
                                    <?php
                                    // Fetch items for this specific order
                                    $item_sql = "SELECT oi.quantity, oi.price_at_purchase, p.name, p.image 
                                                 FROM order_items oi 
                                                 JOIN products p ON oi.product_id = p.id 
                                                 WHERE oi.order_id = ?";
                                    if ($item_stmt = $conn->prepare($item_sql)) {
                                        $item_stmt->bind_param("i", $order_id);
                                        $item_stmt->execute();
                                        $items_result = $item_stmt->get_result();
                                        
                                        while ($item = $items_result->fetch_assoc()) {
                                            ?>
                                            <div class="d-flex align-items-center mb-2">
                                                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
                                                <div>
                                                    <span class="d-block"><?php echo $item['name']; ?></span>
                                                    <small class="text-secondary">Qty: <?php echo $item['quantity']; ?> @ #<?php echo number_format($item['price_at_purchase']); ?></small>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4 border-start border-secondary text-end">
                                <h6 class="text-secondary">Delivery To:</h6>
                                <p class="small mb-2"><?php echo $order['shipping_address']; ?></p>
                                <p class="small mb-3"><i class="fa-solid fa-phone me-1"></i> <?php echo $order['phone_number']; ?></p>
                                <h4 style="color: orangered;">Total: #<?php echo number_format($order['total_amount']); ?></h4>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            } else {
                echo '<div class="text-center py-5">
                        <i class="fa-solid fa-cart-ghost fa-3x mb-3 text-secondary"></i>
                        <p class="text-secondary">You haven\'t placed any orders yet.</p>
                        <a href="gymproj.php" class="btn btn-warning" style="background-color: orangered; border: none; color: white;">Start Shopping</a>
                      </div>';
            }
            $stmt->close();
        }
        $conn->close();
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>