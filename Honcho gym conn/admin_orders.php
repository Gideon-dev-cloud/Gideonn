<?php
// 1. SECURITY & CONFIG
include 'admin_check.php';
require_once 'db_config.php';

// 2. FETCH ALL ORDERS
// We join the 'users' table to show who bought the item
$sql = "SELECT o.id, u.first_name, u.last_name, u.email, o.total_price, o.status, o.created_at 
        FROM orders o 
        JOIN users u ON o.user_id = u.id 
        ORDER BY o.created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History | Honcho Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .table-card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); overflow: hidden; }
        .status-badge { font-size: 0.85em; padding: 6px 12px; border-radius: 20px; font-weight: 600; letter-spacing: 0.5px; }
        .btn-action { transition: 0.2s; }
        .btn-action:hover { transform: translateY(-2px); }
    </style>
</head>
<body class="p-4">

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1"><i class="fas fa-file-invoice-dollar text-primary me-2"></i>Order Management</h2>
            <p class="text-secondary mb-0">View and manage all customer transactions.</p>
        </div>
        <a href="admin_dashboard.php" class="btn btn-dark shadow-sm"><i class="fas fa-arrow-left me-2"></i>Back to Dashboard</a>
    </div>

    <div class="card table-card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-secondary text-uppercase small">
                        <tr>
                            <th class="ps-4 py-3">Order ID</th>
                            <th>Customer Info</th>
                            <th>Date Placed</th>
                            <th>Total Amount</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result && $result->num_rows > 0): ?>
                            <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 fw-bold text-muted">#<?php echo $row['id']; ?></td>
                                    
                                    <td>
                                        <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                                        <div class="small text-muted"><?php echo htmlspecialchars($row['email']); ?></div>        
                                    </td>

                                    <td class="text-secondary">
                                        <i class="far fa-calendar-alt me-1"></i> 
                                        <?php echo date('M d, Y', strtotime($row['created_at'])); ?>
                                    </td>

                                    <td class="fw-bold text-dark">#<?php echo number_format($row['total_price']); ?></td>

                                    <td>
                                        <?php 
                                            $status = $row['status'];
                                            if ($status == 'Pending') {
                                                echo '<span class="status-badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>';
                                            } elseif ($status == 'Completed') {
                                                echo '<span class="status-badge bg-success text-white"><i class="fas fa-check-circle me-1"></i> Completed</span>';
                                            } else {
                                                echo '<span class="status-badge bg-danger text-white"><i class="fas fa-times-circle me-1"></i> Cancelled</span>';
                                            }
                                        ?>
                                    </td>

                                    <td class="text-end pe-4">
                                        <a href="admin_order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-outline-primary btn-action fw-bold">
                                            Manage <i class="fas fa-chevron-right ms-1"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3 opacity-25"></i>
                                        <h5>No orders found yet.</h5>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>