<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION["loggedin"])) { header("location: login.php"); exit; }

$user_id = $_SESSION["id"];

// Fetch cart items joined with products to get names and images
$sql = "SELECT c.product_id, c.quantity, p.name, p.price, p.image 
        FROM cart c 
        JOIN products p ON c.product_id = p.id 
        WHERE c.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total_cart_value = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart | Honcho's GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background-color: #0d0d0d; color: white; }
        .cart-item { background: #1a1a1a; border: 1px solid #333; border-radius: 10px; margin-bottom: 15px; padding: 15px; }
        .product-img { width: 80px; height: 80px; object-fit: cover; border-radius: 8px; }
        .checkout-box { background: #1a1a1a; border: 1px solid orangered; border-radius: 12px; padding: 20px; position: sticky; top: 20px; }
    </style>
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4" style="color: orangered;"><i class="fa-solid fa-cart-shopping me-2"></i> YOUR SHOPPING CART</h2>

    <?php if(isset($_GET['status']) && $_GET['status'] == 'removed'): ?>
        <div class="alert alert-warning border-0 bg-dark text-warning">Item removed successfully.</div>
    <?php endif; ?>

    <div class="row">
        <div class="col-lg-8">
            <?php if ($result->num_rows > 0): ?>
                <?php while($item = $result->fetch_assoc()): 
                    $subtotal = $item['price'] * $item['quantity'];
                    $total_cart_value += $subtotal;
                ?>
                <div class="cart-item d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo $item['image']; ?>" class="product-img me-3" alt="Product">
                        <div>
                            <h5 class="mb-0"><?php echo $item['name']; ?></h5>
                            <small class="text-secondary">Price: #<?php echo number_format($item['price']); ?></small>
                        </div>
                    </div>
                    <div class="text-end">
                        <p class="mb-1 fw-bold">#<?php echo number_format($subtotal); ?></p>
                        <a href="remove_from_cart.php?id=<?php echo $item['product_id']; ?>" 
                           class="btn btn-sm btn-outline-danger" 
                           onclick="return confirm('Remove this from your cart?');">
                            <i class="fa-solid fa-trash-can"></i>
                        </a>
                    </div>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-cart-ghost fa-4x text-secondary mb-3"></i>
                    <h4>Your cart is empty.</h4>
                    <a href="gymproj.php" class="btn btn-warning mt-3" style="background-color: orangered; color: white; border:none;">GO SHOPPING</a>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-lg-4">
            <div class="checkout-box">
                <h4>Order Summary</h4>
                <hr class="text-secondary">
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <span>#<?php echo number_format($total_cart_value); ?></span>
                </div>
                <div class="d-flex justify-content-between mb-4">
                    <span>Shipping</span>
                    <span class="text-success">FREE</span>
                </div>
                <div class="d-flex justify-content-between fw-bold fs-5 mb-4">
                    <span>Total</span>
                    <span style="color: orangered;">#<?php echo number_format($total_cart_value); ?></span>
                </div>
                <a href="checkout.php" class="btn btn-lg w-100 <?php echo ($total_cart_value == 0) ? 'disabled btn-secondary' : ''; ?>" style="background-color: orangered; color: white;">
                    PROCEED TO CHECKOUT
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>