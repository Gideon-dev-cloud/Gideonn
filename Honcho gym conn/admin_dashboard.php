<?php
// 1. SECURITY & CONFIGURATION
include 'admin_check.php';
require_once 'db_config.php';

// Helper: Safely fetch a single value (Prevents "Bool" crashes)
function get_stat($conn, $sql) {
    $result = $conn->query($sql);
    if (!$result) return 0; // Return 0 if table/column doesn't exist
    $row = $result->fetch_row();
    return $row[0] ?? 0;
}

// 2. GATHER DATA (The "All-Powerful" Analytics)
$revenue    = get_stat($conn, "SELECT SUM(total_price) FROM orders");
$pending    = get_stat($conn, "SELECT COUNT(*) FROM orders WHERE status = 'Pending'");
$customers  = get_stat($conn, "SELECT COUNT(*) FROM users WHERE is_admin = 0");
$products   = get_stat($conn, "SELECT COUNT(*) FROM products");

// Check for Low Stock (Only runs if 'quantity' column exists)
$low_stock = 0;
$check_col = $conn->query("SHOW COLUMNS FROM products LIKE 'quantity'");
if ($check_col && $check_col->num_rows > 0) {
    $low_stock = get_stat($conn, "SELECT COUNT(*) FROM products WHERE quantity < 5");
}

// 3. RECENT ORDERS (Live Feed)
$feed_sql = "SELECT o.id, u.first_name, u.last_name, o.total_price, o.status, o.created_at 
             FROM orders o 
             JOIN users u ON o.user_id = u.id 
             ORDER BY o.id DESC LIMIT 6";
$feed_res = $conn->query($feed_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honcho HQ | Command Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    
    <style>
        :root { --sidebar-bg: #1a1a1a; --accent: #ff4500; }
        body { background-color: #f0f2f5; font-family: 'Segoe UI', sans-serif; overflow-x: hidden; }
        
        /* SIDEBAR STYLING */
        .sidebar { min-height: 100vh; background: var(--sidebar-bg); color: #fff; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .brand { font-size: 1.5rem; font-weight: bold; letter-spacing: 1px; color: var(--accent); }
        .nav-link { color: #b0b0b0; padding: 15px 20px; transition: 0.3s; border-radius: 8px; margin-bottom: 5px; }
        .nav-link:hover, .nav-link.active { background: rgba(255, 69, 0, 0.15); color: #fff; transform: translateX(5px); }
        .nav-link i { width: 25px; text-align: center; color: var(--accent); }
        
        /* DASHBOARD CARDS */
        .stat-card { border: none; border-radius: 15px; background: white; transition: transform 0.3s ease, box-shadow 0.3s ease; overflow: hidden; position: relative; }
        .stat-card:hover { transform: translateY(-8px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .stat-icon { font-size: 2.5rem; opacity: 0.2; position: absolute; right: 20px; bottom: 15px; }
        .card-label { font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; color: #888; font-weight: 600; }
        .card-value { font-size: 2rem; font-weight: 700; color: #333; }
        
        /* TABLE STYLING */
        .table-card { border-radius: 15px; border: none; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }
        .table thead { background: #333; color: white; }
        .status-badge { padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; letter-spacing: 0.5px; }
    </style>
</head>
<body>

<div class="container-fluid">
    <div class="row">
        
        <nav class="col-md-3 col-lg-2 sidebar p-4 d-flex flex-column">
            <div class="mb-5 d-flex align-items-center gap-2">
                <i class="fas fa-dumbbell fa-2x text-warning" style="color: var(--accent)!important;"></i>
                <span class="brand">HONCHO HQ</span>
            </div>
            
            <ul class="nav flex-column mb-auto">
                <li class="nav-item"><a href="admin_dashboard.php" class="nav-link active"><i class="fas fa-chart-pie"></i> Overview</a></li>
                <li class="nav-item"><a href="admin_orders.php" class="nav-link"><i class="fas fa-shipping-fast"></i> Manage Orders</a></li>
                <li class="nav-item"><a href="admin_products.php" class="nav-link"><i class="fas fa-boxes"></i> Inventory</a></li>
                <li class="nav-item"><a href="admin_customers.php" class="nav-link"><i class="fas fa-users"></i> Customers</a></li>
            </ul>
            
            <div class="mt-auto">
                <div class="p-3 rounded bg-dark mb-3 text-center border border-secondary">
                    <small class="text-muted d-block mb-1">Logged in as</small>
                    <strong><?php echo $_SESSION['name'] ?? 'Admin'; ?></strong>
                </div>
                <a href="logout.php" class="nav-link text-danger"><i class="fas fa-power-off"></i> Logout</a>
            </div>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 p-4 p-md-5">
            
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold mb-1">Dashboard</h2>
                    <p class="text-secondary mb-0">Here's what's happening with your business today.</p>
                </div>
                <a href="gymproj.php" target="_blank" class="btn btn-outline-dark rounded-pill px-4">
                    <i class="fas fa-external-link-alt me-2"></i> Visit Shop
                </a>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card p-4 border-start border-5 border-success">
                        <span class="card-label">Total Revenue</span>
                        <div class="card-value mt-2">#<?php echo number_format($revenue); ?></div>
                        <i class="fas fa-wallet stat-icon text-success"></i>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card p-4 border-start border-5 border-warning">
                        <span class="card-label">Pending Orders</span>
                        <div class="card-value mt-2"><?php echo $pending; ?></div>
                        <i class="fas fa-stopwatch stat-icon text-warning"></i>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card p-4 border-start border-5 border-danger">
                        <span class="card-label">Low Stock Items</span>
                        <div class="card-value mt-2"><?php echo $low_stock; ?></div>
                        <i class="fas fa-exclamation-triangle stat-icon text-danger"></i>
                    </div>
                </div>

                <div class="col-md-6 col-xl-3">
                    <div class="card stat-card p-4 border-start border-5 border-primary">
                        <span class="card-label">Total Customers</span>
                        <div class="card-value mt-2"><?php echo $customers; ?></div>
                        <i class="fas fa-users stat-icon text-primary"></i>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-xl-8">
                    <div class="card table-card h-100">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">Recent Transactions</h5>
                            <a href="admin_orders.php" class="text-decoration-none small text-primary fw-bold">View All</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Order ID</th>
                                        <th>Customer</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if ($feed_res && $feed_res->num_rows > 0): ?>
                                        <?php while($row = $feed_res->fetch_assoc()): ?>
                                        <tr>
                                            <td class="ps-4 fw-bold text-muted">#<?php echo $row['id']; ?></td>
                                            <td>
                                                <div class="fw-bold"><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></div>
                                                <small class="text-muted"><?php echo date('M d, Y', strtotime($row['created_at'])); ?></small>
                                            </td>
                                            <td class="fw-bold">#<?php echo number_format($row['total_price']); ?></td>
                                            <td>
                                                <?php if($row['status'] == 'Pending'): ?>
                                                    <span class="status-badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Pending</span>
                                                <?php elseif($row['status'] == 'Completed'): ?>
                                                    <span class="status-badge bg-success text-white"><i class="fas fa-check me-1"></i> Paid</span>
                                                <?php else: ?>
                                                    <span class="status-badge bg-secondary text-white"><?php echo $row['status']; ?></span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end pe-4">
                                                <a href="admin_order_details.php?id=<?php echo $row['id']; ?>" class="btn btn-sm btn-light border"><i class="fas fa-arrow-right"></i></a>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    <?php else: ?>
                                        <tr><td colspan="5" class="text-center py-5 text-muted">No orders found yet.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4">
                    <div class="card table-card h-100 p-4">
                        <h5 class="fw-bold mb-4">Quick Actions</h5>
                        <div class="d-grid gap-3">
                            <a href="admin_add_product.php" class="btn btn-dark py-3 text-start shadow-sm">
                                <i class="fas fa-plus-circle me-2 text-warning"></i> Add New Product
                            </a>
                            <a href="admin_orders.php" class="btn btn-light border py-3 text-start">
                                <i class="fas fa-box-open me-2 text-primary"></i> Process Pending Orders
                            </a>
                            <a href="admin_customers.php" class="btn btn-light border py-3 text-start">
                                <i class="fas fa-user-plus me-2 text-success"></i> Manage Users
                            </a>
                            <div class="alert alert-info border-0 d-flex align-items-center mt-2">
                                <i class="fas fa-info-circle fa-2x me-3"></i>
                                <small>Remember to check inventory levels every Friday.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php
include  'toast_handler.php';
?>
</body>
</html>