<?php
include 'admin_check.php';
require_once 'db_config.php';

// HANDLE ADD PRODUCT
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    // Image Upload Logic
    $target_dir = "uploads/";
    // Create folder if it doesn't exist
    if (!file_exists($target_dir)) { mkdir($target_dir, 0777, true); }
    
    $image_name = basename($_FILES["image"]["name"]);
    $target_file = $target_dir . time() . "_" . $image_name; // unique name
    
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
        // Insert into DB
        $stmt = $conn->prepare("INSERT INTO products (name, price, quantity, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdis", $name, $price, $quantity, $target_file);
        $stmt->execute();
        $msg = "Product added successfully!";
    } else {
        $error = "Failed to upload image.";
    }
}

// HANDLE DELETE PRODUCT
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE id = $id");
    header("Location: admin_products.php"); // Refresh page
}

// FETCH PRODUCTS
$products = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <div class="d-flex justify-content-between mb-4">
            <h2>Inventory Management</h2>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card shadow-sm p-4">
                    <h5>Add New Item</h5>
                    <?php if(isset($msg)) echo "<div class='alert alert-success'>$msg</div>"; ?>
                    <?php if(isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Product Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Price (#)</label>
                            <input type="number" name="price" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Quantity (Stock)</label>
                            <input type="number" name="quantity" class="form-control" value="10" required>
                        </div>
                        <div class="mb-3">
                            <label>Product Image</label>
                            <input type="file" name="image" class="form-control" required>
                        </div>
                        <button type="submit" name="add_product" class="btn btn-primary w-100">Add to Shop</button>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card shadow-sm">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-dark">
                            <tr><th>Img</th><th>Name</th><th>Price</th><th>Stock</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <?php while($row = $products->fetch_assoc()): ?>
                            <tr>
                                <td><img src="<?php echo $row['image']; ?>" width="50" style="border-radius:5px;"></td>
                                <td><?php echo $row['name']; ?></td>
                                <td>#<?php echo number_format($row['price']); ?></td>
                                <td>
                                    <span class="badge <?php echo ($row['quantity'] < 5) ? 'bg-danger' : 'bg-success'; ?>">
                                        <?php echo $row['quantity']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="admin_products.php?delete=<?php echo $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this item?');">Delete</a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>