<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Success | Honcho's GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body style="background-color: #0d0d0d; color: white; display: flex; align-items: center; justify-content: center; height: 100vh;">
    <div class="text-center">
        <i class="fa-solid fa-circle-check fa-5x mb-4" style="color: orangered;"></i>
        <h1 class="display-4">ORDER PLACED!</h1>
        <p class="lead">Your gear is being prepped. Order ID: #<?php echo $_GET['order_id']; ?></p>
        <a href="dashboard.php" class="btn btn-lg mt-4" style="background-color: orangered; color: white;">Return to Dashboard</a>
    </div>
</body>
</html>