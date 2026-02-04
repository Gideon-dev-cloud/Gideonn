<?php
session_start();
require_once 'db_config.php';

// Fetch ONLY items marked as Supplements
$sql = "SELECT * FROM products WHERE category = 'Supplements' ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplements | Honcho's GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background-color: #0d0d0d; color: white; }
        .product-card { background: #1a1a1a; border: 1px solid #333; transition: 0.3s; border-radius: 12px; }
        .product-card:hover { border-color: orangered; transform: translateY(-5px); }
        .badge-nutrition { background: #00ffcc; color: black; font-size: 0.7rem; font-weight: bold; }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: orangered;">FUEL YOUR PERFORMANCE</h2>
        <p class="text-secondary">Premium nutrition to support your grind.</p>
    </div>

    <div class="row g-4">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="product-card h-100 p-3">
                <span class="badge badge-nutrition mb-2">SUPPLEMENT</span>
                <img src="<?php echo $row['image']; ?>" class="img-fluid rounded mb-3" alt="Supp" onerror="this.src='./img/placeholder.png'">
                <h5><?php echo $row['name']; ?></h5>
                <p class="fw-bold" style="color: orangered;">#<?php echo number_format($row['price']); ?></p>
                
                <form action="add_to_cart.php" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" class="btn btn-sm btn-outline-light w-100 mt-2">Add to Stack</button>
                </form>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
</body>
</html>