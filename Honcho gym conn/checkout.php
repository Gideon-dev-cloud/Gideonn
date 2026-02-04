<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION["loggedin"])) { header("location: login.php"); exit; }
$user_id = $_SESSION["id"];

// Fetch cart total
$sql = "SELECT SUM(p.price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$cart_total = $stmt->get_result()->fetch_assoc()['total'] ?? 0;

if ($cart_total == 0) { header("location: gymproj.php"); exit; }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Honcho's GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #0d0d0d; color: white; }
        .checkout-card { background: #1a1a1a; border: 1px solid #333; border-radius: 15px; }
        .form-control { background: #000; border: 1px solid #444; color: white; }
        .form-control:focus { background: #000; color: white; border-color: #ff4500; box-shadow: none; }
    </style>
</head>
<body>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="checkout-card p-4 p-md-5">
                <h2 class="mb-4" style="color: #ff4500;">SECURE CHECKOUT</h2>
                <p class="text-secondary">Total Amount to Pay: <strong class="text-white">#<?php echo number_format($cart_total); ?></strong></p>
                <hr class="border-secondary">
                
                <form action="process_checkout.php" method="POST">
                    <div class="mb-3">
                        <label class="small text-secondary">Full Delivery Address</label>
                        <textarea name="address" class="form-control" rows="3" placeholder="Street, City, State" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="small text-secondary">Phone Number</label>
                        <input type="text" name="phone" class="form-control" placeholder="08012345678" required>
                    </div>
                    <div class="mb-4">
                        <label class="small text-secondary">Payment Method</label>
                        <select class="form-control" disabled>
                            <option>Payment on Delivery (Honcho Pay)</option>
                        </select>
                        <small class="text-muted italic">*Online payment integration coming soon.</small>
                    </div>
                    <button type="submit" class="btn w-100 py-3 fw-bold" style="background: #ff4500; color: white;">CONFIRM ORDER</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>