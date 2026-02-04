<?php
include 'admin_check.php';
require_once 'db_config.php';

// 1. Check if ID is provided
if (!isset($_GET['id'])) { 
    header("Location: admin_orders.php"); 
    exit; 
}
$order_id = intval($_GET['id']); // Clean the ID

// 2. Handle Status Update
if (isset($_POST['update_status'])) {
    $status = $_POST['status'];
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $order_id);
    
    if($stmt->execute()){
        $msg = "Order status updated to <strong>$status</strong>";
    } else {
        $error = "Failed to update status.";
    }
}

// 3. Fetch Order & Customer Info (Safe Version)
$order_sql = "SELECT o.*, u.first_name, u.last_name, u.email, u.phone
              FROM orders o 
              JOIN users u ON o.user_id = u.id 
              WHERE o.id = $order_id";

$order_query = $conn->query($order_sql);

if (!$order_query || $order_query->num_rows == 0) {
    die("Error: Order not found or Database Error. " . $conn->error);
}
$order = $order_query->fetch_assoc();

// 4. Fetch Items (Safe Version)
// We use the variable name '$items_result' here
$items_sql = "SELECT oi.*, p.name, p.image 
              FROM order_items oi 
              JOIN products p ON oi.product_id = p.id 
              WHERE oi.order_id = $order_id";

$items_result = $conn->query($items_sql);

// Check if query failed (usually due to missing table)
if (!$items_result) {
    die("Error fetching items: " . $conn->error . "<br>Did you create the 'order_items' table?");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order #<?php echo $order_id; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <a href="admin_orders.php" class="btn btn-outline-dark mb-4">← Back to Orders</a>
        
        <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
        <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

        <div class="row">
            <div class="col-md-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white"><strong>Order Contents</strong></div>
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($items_result->num_rows > 0): ?>
                                <?php while($item = $items_result->fetch_assoc()): ?>
                                <tr>
                                    <td>
                                        <img src="<?php echo !empty($item['image']) ? $item['image'] : 'uploads/default.jpg'; ?>" 
                                             width="50" height="50" style="object-fit:cover; border-radius:5px;" class="me-2"> 
                                        <?php echo htmlspecialchars($item['name']); ?>
                                    </td>
                                    <td><?php echo $item['quantity']; ?></td>
                                    <td>#<?php echo number_format($item['price']); ?></td>
                                    <td>#<?php echo number_format($item['price'] * $item['quantity']); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr><td colspan="4" class="text-center text-muted">No items found for this order.</td></tr>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td class="fw-bold fs-5">#<?php echo number_format($order['total_price']); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Customer Details</h5>
                        <hr>
                        <p class="mb-1"><strong>Name:</strong> <?php echo $order['first_name'] . " " . $order['last_name']; ?></p>
                        <p class="mb-1"><strong>Email:</strong> <?php echo $order['email']; ?></p>
                        <p class="mb-1"><strong>Phone:</strong><?php echo $order['phone_number'] ?? 'N/A'; ?></p></p>
                        <p class="text-muted small mt-3">Order Date: <?php echo date("M d, Y h:i A", strtotime($order['created_at'])); ?></p>
                    </div>
                </div>

                <div class="card shadow-sm border-warning">
                    <div class="card-body">
                        <h5>Fulfillment Status</h5>
                        <form method="POST">
                            <select name="status" class="form-select mb-3">
                                <option value="Pending" <?php if($order['status']=='Pending') echo 'selected'; ?>>Pending</option>
                                <option value="Completed" <?php if($order['status']=='Completed') echo 'selected'; ?>>Completed (Shipped)</option>
                                <option value="Cancelled" <?php if($order['status']=='Cancelled') echo 'selected'; ?>>Cancelled</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-warning w-100 fw-bold">Update Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>