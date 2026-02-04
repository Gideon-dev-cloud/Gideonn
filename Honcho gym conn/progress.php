<?php
session_start();
require_once 'db_config.php';

if (!isset($_SESSION["loggedin"])) { header("location: login.php"); exit; }

$user_id = $_SESSION["id"];

// Fetch PR history for the chart (Limit to last 10 for better mobile viewing)
$chart_labels = [];
$chart_data = [];
$chart_sql = "SELECT weight_kg, date_logged FROM user_progress WHERE user_id = ? AND exercise_name = 'Bench Press' ORDER BY date_logged ASC LIMIT 10";
$c_stmt = $conn->prepare($chart_sql);
$c_stmt->bind_param("i", $user_id);
$c_stmt->execute();
$c_res = $c_stmt->get_result();
while($c_row = $c_res->fetch_assoc()) {
    $chart_labels[] = date('M d', strtotime($c_row['date_logged']));
    $chart_data[] = $c_row['weight_kg'];
}

// Fetch history for the table
$history_sql = "SELECT * FROM user_progress WHERE user_id = ? ORDER BY date_logged DESC";
$stmt = $conn->prepare($history_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$history_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Tracker | Honcho's GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { background-color: #0d0d0d; color: white; font-family: 'Segoe UI', sans-serif; }
        .card { background-color: #1a1a1a; border: 1px solid #333; border-radius: 12px; }
        .form-control, .form-select { background-color: #252525; color: white; border: 1px solid #444; }
        .form-control:focus { background-color: #252525; color: white; border-color: orangered; box-shadow: none; }
        .table-responsive { border-radius: 8px; overflow: hidden; }
        
        /* Ensures the chart doesn't get too tall on mobile */
        .chart-container { position: relative; height: 300px; width: 100%; }
    </style>
</head>
<body>

<div class="container my-4 my-md-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <?php if(isset($_GET['deleted'])): ?>
    <div class="alert alert-success alert-dismissible fade show bg-dark text-success border-success" role="alert">
        <strong>Deleted!</strong> Your record has been removed.
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>
        <h2 style="color: orangered;">PROGRESS TRACKER</h2>
        <a href="dashboard.php" class="btn btn-outline-light btn-sm">Dashboard</a>
    </div>

    
    <div class="row mb-4">
        <div class="col-12">
            <div class="card p-3 p-md-4">
                <h5 class="mb-3">Bench Press Strength Curve</h5>
                <div class="chart-container">
                    <canvas id="progressChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-lg-4">
            <div class="card p-4">
                <h4 class="mb-3">Log a Lift</h4>
                <form action="log_progress.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Exercise</label>
                        <select name="exercise_name" class="form-select" required>
                            <option value="Bench Press">Bench Press</option>
                            <option value="Squat">Squat</option>
                            <option value="Deadlift">Deadlift</option>
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label small text-secondary">Weight (kg)</label>
                            <input type="number" step="0.1" name="weight" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label small text-secondary">Reps</label>
                            <input type="number" name="reps" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-secondary">Date</label>
                        <input type="date" name="date_logged" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                    </div>
                    <button type="submit" class="btn w-100 fw-bold" style="background-color: orangered; color: white;">SAVE PROGRESS</button>
                </form>
            </div>
        </div>

        <div class="col-12 col-lg-8">
            <div class="card p-4">
                <h4 class="mb-3">History</h4>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr class="text-secondary">
                                <th>Date</th>
                                <th>Exercise</th>
                                <th>Weight</th>
                                <th>Reps</th>
                            </tr>
                        </thead>
                        <tbody>
    <?php if ($history_result->num_rows > 0): ?>
        <?php while($row = $history_result->fetch_assoc()): ?>
        <tr>
            <td class="small"><?php echo date('M d', strtotime($row['date_logged'])); ?></td>
            <td><?php echo $row['exercise_name']; ?></td>
            <td class="fw-bold"><?php echo $row['weight_kg']; ?>kg</td>
            <td><?php echo $row['reps']; ?></td>
            <td class="text-end">
                <a href="delete_progress.php?id=<?php echo $row['id']; ?>" 
                   class="btn btn-sm btn-outline-danger" 
                   onclick="return confirm('Are you sure you want to delete this PR?');">
                   <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
        </tr>
        <?php endwhile; ?>
    <?php else: ?>
        <tr><td colspan="5" class="text-center text-secondary">No lifts logged yet.</td></tr>
    <?php endif; ?>
</tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Chart.js Configuration
    const ctx = document.getElementById('progressChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chart_labels); ?>,
            datasets: [{
                label: 'Bench Press (kg)',
                data: <?php echo json_encode($chart_data); ?>,
                borderColor: 'orangered',
                backgroundColor: 'rgba(255, 69, 0, 0.1)',
                borderWidth: 3,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: 'white'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, // Essential for responsive height
            scales: {
                y: { grid: { color: '#333' }, ticks: { color: '#888' } },
                x: { grid: { display: false }, ticks: { color: '#888' } }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

</body>
</html>