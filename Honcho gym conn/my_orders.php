<?php
include 'db_config.php';
session_start();
if (!isset($_SESSION['loggedin'])) { header("Location: login.php"); exit; }

$user_id = $_SESSION['id'];
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="container">
        <h2 class="mb-4">My Order History</h2>
        <div class="card shadow-sm border-0">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr><th>Order #</th><th>Date</th><th>Total</th><th>Status</th></tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td>#<?php echo $row['id']; ?></td>
                        <td><?php echo date('M d, Y', strtotime($row['created_at'])); ?></td>
                        <td class="fw-bold">#<?php echo number_format($row['total_price']); ?></td>
                        <td>
                            <span class="badge <?php echo ($row['status']=='Completed')?'bg-success':'bg-warning'; ?>">
                                <?php echo $row['status']; ?>
                            </span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <a href="gymproj.php" class="btn btn-dark mt-3">Back to Shop</a>
    </div>
</body>
</html>