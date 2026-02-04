<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION["id"];
$user_name = $_SESSION["name"];
$today = date('Y-m-d');

// --- DATA QUERIES ---

// 1. Total Orders
$order_count = 0;
$res = $conn->query("SELECT COUNT(*) as total FROM orders WHERE user_id = $user_id");
if($row = $res->fetch_assoc()) { $order_count = $row['total']; }

// 2. Best Bench Press (Strength PR)
$best_bench = "---";
$res = $conn->query("SELECT MAX(weight_kg) as max_wt FROM user_progress WHERE user_id = $user_id AND exercise_name = 'Bench Press'");
if($row = $res->fetch_assoc()) { $best_bench = $row['max_wt'] ? $row['max_wt'] . " kg" : "---"; }

// 3. Today's Nutrition Stats
$protein_total = 0;
$calorie_total = 0;
$nutri_res = $conn->query("SELECT SUM(protein_grams) as p, SUM(calories) as c FROM nutrition_logs WHERE user_id = $user_id AND log_date = '$today'");
if($nutri_row = $nutri_res->fetch_assoc()) {
    $protein_total = $nutri_row['p'] ?? 0;
    $calorie_total = $nutri_row['c'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honcho's Command Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        :root { --honcho-orange: #ff4500; --honcho-bg: #0d0d0d; --honcho-card: #1a1a1a; }
        body { background-color: var(--honcho-bg); color: white; font-family: 'Inter', sans-serif; }
        
        .card-custom { background: var(--honcho-card); border: 1px solid #333; border-radius: 15px; transition: 0.3s; }
        .card-custom:hover { border-color: var(--honcho-orange); }
        
        .stat-icon { width: 45px; height: 45px; background: rgba(255, 69, 0, 0.1); color: var(--honcho-orange); 
                     display: flex; align-items: center; justify-content: center; border-radius: 10px; font-size: 1.2rem; }
        
        .nav-link-custom { color: #888; text-decoration: none; padding: 10px; display: block; border-radius: 8px; transition: 0.2s; }
        .nav-link-custom:hover, .nav-link-custom.active { color: white; background: var(--honcho-orange); }
        
        .progress { background-color: #000; height: 8px; border-radius: 10px; }
        .progress-bar-protein { background-color: #00ffcc; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-black d-md-none p-3 border-bottom border-secondary">
    <span class="navbar-brand fw-bold" style="color: var(--honcho-orange);">HONCHO'S</span>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu">
        <span class="navbar-toggler-icon"></span>
    </button>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav class="col-md-3 col-lg-2 d-md-block bg-black sidebar collapse p-4 border-end border-secondary" id="sidebarMenu" style="min-height: 100vh;">
            <div class="d-none d-md-block text-center mb-5">
                <h4 class="fw-bold" style="color: var(--honcho-orange);">HONCHO'S GYM</h4>
            </div>
            <a href="dashboard.php" class="nav-link-custom active mb-2"><i class="fas fa-th-large me-2"></i> Dashboard</a>
            <a href="gymproj.php" class="nav-link-custom mb-2"><i class="fas fa-dumbbell me-2"></i> Shop Gear</a>
            <a href="supplements.php" class="nav-link-custom mb-2"><i class="fas fa-capsules me-2"></i> Supplements</a>
            <a href="progress.php" class="nav-link-custom mb-2"><i class="fas fa-chart-line me-2"></i> PR Tracker</a>
            <a href="view_orders.php" class="nav-link-custom mb-2"><i class="fas fa-truck me-2"></i> My Orders</a>
            <hr class="text-secondary mt-4">
            <a href="logout.php" class="nav-link-custom text-danger"><i class="fas fa-sign-out-alt me-2"></i> Logout</a>
        </nav>

        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
            
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold mb-0">Hello, <?php echo $user_name; ?>!</h2>
                    <p class="text-secondary small">Consistency is the bridge between goals and accomplishment.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="gymproj.php" class="btn btn-outline-light btn-sm">Shop New Gear</a>
                    <button class="btn btn-sm btn-warning" style="background-color: var(--honcho-orange); border:none; color:white;" data-bs-toggle="modal" data-bs-target="#logModal">Log Daily Intake</button>
                </div>
            </div>

            
            <div class="row g-4 mb-4">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card-custom p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon"><i class="fas fa-weight-hanging"></i></div>
                            <span class="text-secondary small">Bench Press</span>
                        </div>
                        <h3 class="fw-bold"><?php echo $best_bench; ?></h3>
                        <p class="text-secondary small mb-0">Your Personal Record</p>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card-custom p-4 h-100">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="stat-icon"><i class="fas fa-box"></i></div>
                            <span class="text-secondary small">Gear Status</span>
                        </div>
                        <h3 class="fw-bold"><?php echo $order_count; ?> Items</h3>
                        <a href="view_orders.php" class="text-decoration-none small" style="color: var(--honcho-orange);">Track Shipments →</a>
                    </div>
                </div>

                
                <div class="col-12 col-xl-6">
                    <div class="card-custom p-4 h-100">
                        <h6 class="text-secondary mb-4">TODAY'S FUEL INTAKE</h6>
                        <div class="row align-items-center g-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Protein Intake</span>
                                    <span><?php echo $protein_total; ?>g / 180g</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar-protein progress-bar" style="width: <?php echo min(($protein_total/180)*100, 100); ?>%"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between small mb-1">
                                    <span>Calorie Goal</span>
                                    <span><?php echo $calorie_total; ?> / 2,500</span>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar bg-warning" style="width: <?php echo min(($calorie_total/2500)*100, 100); ?>%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-md-8">
                    <div class="card-custom p-4">
                        <h5 class="fw-bold mb-3">Recommended Training</h5>
                        <div class="d-flex align-items-center p-3 bg-black rounded border border-secondary mb-2">
                            <i class="fas fa-play-circle fa-2x me-3 text-secondary"></i>
                            <div>
                                <p class="mb-0 fw-bold">Mastering the Leg Press</p>
                                <small class="text-secondary">Avoid common injuries and maximize quad growth.</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-custom p-4 text-center border-0" style="background: linear-gradient(135deg, #ff4500 0%, #ff8c00 100%);">
                        <h5 class="fw-bold">Honcho Plus</h5>
                        <p class="small">Unlock customized meal plans and advanced lift analytics.</p>
                        <button class="btn btn-dark btn-sm rounded-pill w-100 fw-bold">Upgrade Now</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</div>

<div class="modal fade" id="logModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary text-white p-3">
            <form action="save_nutrition.php" method="POST">
                <h5 class="modal-title mb-3">Log Nutrition</h5>
                <div class="mb-3">
                    <label class="small text-secondary">Protein (grams)</label>
                    <input type="number" name="protein" class="form-control bg-black text-white border-secondary" required>
                </div>
                <div class="mb-3">
                    <label class="small text-secondary">Calories</label>
                    <input type="number" name="calories" class="form-control bg-black text-white border-secondary" required>
                </div>
                <button type="submit" class="btn w-100" style="background-color: var(--honcho-orange); color: white;">Save Today's Log</button>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<a href="cart.php" class="btn rounded-circle shadow-lg d-flex align-items-center justify-content-center" 
   style="position: fixed; bottom: 30px; right: 30px; width: 70px; height: 70px; background-color: orangered; border: 2px solid white; z-index: 1000;">
    <i class="fa-solid fa-cart-shopping fa-2x text-white"></i>
</a>
</body>
</html>