
<?php
session_start();
require_once 'db_config.php';

// --- PROFESSIONAL SEARCH LOGIC ---



    $search = trim($_GET['search'] ?? '');

    if (!empty($search)) {

        $sql = "SELECT * FROM products WHERE name LIKE ? ORDER BY id DESC";
        $stmt = $conn->prepare($sql);

        $search_term = "%" . $search . "%";
        $stmt->bind_param("s", $search_term);

        $stmt->execute();
        $result = $stmt->get_result();

    } else {

        $sql = "SELECT * FROM products ORDER BY id DESC";
        $result = $conn->query($sql);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honcho's GYM</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="./gymproj.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer"/>
</head>
<body>
    <section id="top"> 
     <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid">
            <a class="logo navbar-brand" href="#"><img src="./img/barbell.png" alt="Gym icon"></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
                <ul class="navbar-nav nav-links">
                    <li class="nav-item"><a class="nav-link links" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link links" href="#">Accessories</a></li>

                    <li class="nav-item dropdown">
                        <a class="nav-link links dropdown-toggle" href="#" id="cardioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Cardio
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="cardioDropdown">
                            <li><a class="dropdown-item" href="product_detail.php?category=treadmills">Treadmills</a></li>
                            <li><a class="dropdown-item" href="product_detail.php?category=ellipticals">Ellipticals & Bikes</a></li>
                            <li><a class="dropdown-item" href="product_detail.php?category=rowing">Rowing Machines</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link links dropdown-toggle" href="#" id="strengthDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Strength
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="strengthDropdown">
                            <li><a class="dropdown-item" href="product_detail.php?category=dumbbells">Dumbbells & Plates</a></li>
                            <li><a class="dropdown-item" href="product_detail.php?category=racks">Benches & Racks</a></li>
                            <li><a class="dropdown-item" href="product_detail.php?category=cables">Cable Machines</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link links dropdown-toggle" href="#" id="teamsportsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Team Sports
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="teamsportsDropdown">
                            <li><a class="dropdown-item" href="product_detail.php?category=basketball">Basketball</a></li>
                            <li><a class="dropdown-item" href="product_detail.php?category=soccer">Football/Soccer</a></li>
                            <li><a class="dropdown-item" href="product_detail.php?category=volleyball">Volleyball</a></li>
                        </ul>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link links dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            More
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li class="nav-item"><a class="nav-link" href="supplements.php" style="color: #00ffcc; font-weight: bold;">Supplements</a></li>
                            <li><a class="dropdown-item" href="#">Apparel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Partnerships</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
            <div class="social-links d-flex align-items-center">

    <form action="" method="get" class="d-flex align-items-center me-2">
        <input 
            type="text" 
            name="search"
            class="form-control form-control-sm me-1"
            placeholder="Search product"
            required
        >

        <button type="submit" class="btn btn-sm btn-outline-secondary" name="submit">
            <i class="fa-solid fa-magnifying-glass"></i>
        </button>
    </form>

    <a href="./login.php" class="me-2">
        <i class="fa-solid fa-user"></i>
    </a>

    <p class="num mb-0">+2348021333092</p>

</div>

        </div>
   </nav>
   <div class="text">
    <h1>STAY FIT, STAY HEALTHY</h1>
<h1 style="font-size: 40px;">Nigeria's #1 Premium Gym &
</h1>
<h1 style="font-size: 40px;">Fitness Equipment Supplier Lagos</h1><br>
<h1 style="font-size: 35px;">Trusted by 500+ Gyms, Hotels & Fitness Enthusiasts</h1><br>
<p>Looking for top-notch gym & fitness equipment? We are Nigeria’s premier supplier for commercial gyms,</p>
<p>home use, hotels, schools, and corporate wellness equipment. Get top-quality gear to achieve your fitness </p>
<p>goals efficiently.</p><br><br>
<div class="anchor">
    <a style="background-color: orangered; color: white;padding: 15px 15px; border: solid 1px white; text-decoration: none;" href="#new-arrivals">SHOP EQUIPMENT NOW</a>
    <a style="border:white 1px solid; padding: 15px 15px; text-decoration: none; color: white;" href="">GET FREE EXPERT HELP</a>
</div>
   </div> 
   </section>

<div class="underlings container my-5">
    <div class="row text-center">
        <div class="prices col-md-3">
            <img width ="60" src="./img/Best prices.png" alt="">
            <h3>Best Prices</h3>
            <p>15+ Years as #1 Importer</p>
        </div>
        <div class="delivery col-md-3">
            <img width="60" src="./img/Fast delivery.png" alt="">
            <h3>Fast delivery</h3>
            <p>500+ Equipment Delivered</p>
        </div>
        <div class="Daily col-md-3">
            <img width="60" src="./img/24 daily.png" alt="">
            <h3>24 HOUR DAILY</h3>
            <p>Phone support</p>
        </div>
        <div class="Payment col-md-3">
            <img width="60" src="./img/Credit cards.png" alt="">
            <h3>Secure Payment</h3>
            <p>100% secure payments</p>
        </div>
    </div>
 </div>

 <div id="categoryCarousel" class="carousel slide container my-5" data-bs-ride="carousel">
    <div class="carousel-inner">
        
        <div class="carousel-item active">
            <div class="d-flex justify-content-around text-center">
                <div class="p-2">
                    <img class="d-block w-100 carousel-img" src="./img/Strength.jpg" alt="Strength">
                    <h3>STRENGTH</h3>
                </div>
                <div class="p-2">
                    <img class="d-block w-100 carousel-img" src="./img/Cardio.jpg" alt="Cardio">
                    <h3>CARDIO</h3>
                </div>
            </div>
        </div>

        <div class="carousel-item">
            <div class="d-flex justify-content-around text-center">
                <div class="p-2">
                    <img class="d-block w-100 carousel-img" src="./img/Accessories.jpg" alt="Accessories">
                    <h3>ACCESSORIES</h3>
                </div>
                <div class="p-2">
                    <img class="d-block w-100 carousel-img" src="./img/Team sports.jpg" alt="Team Sports">
                    <h3>TEAM SPORTS</h3>
                </div>
            </div>
        </div>

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#categoryCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#categoryCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<div class="row g-4">
    <?php
    // require_once 'db_config.php';
    
    // // 1. Fetch all products from the database
    // $sql = "SELECT * FROM products ORDER BY id DESC";
    // $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // 2. Loop through each row in the database
        while($row = $result->fetch_assoc()) {
            ?>
            <div class="col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm bg-dark text-white p-2">
                    <img src="<?php echo htmlspecialchars($row['image']); ?>" 
                         class="card-img-top rounded" 
                         alt="<?php echo htmlspecialchars($row['name']); ?>"
                         style="height: 200px; object-fit: cover;">
                    
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold"><?php echo htmlspecialchars($row['name']); ?></h5>
                        <p class="text-warning fs-5">#<?php echo number_format($row['price']); ?></p>
                        
                        <form action="add_to_cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                            <button type="submit" class="btn btn-orange w-100 fw-bold" 
                                    style="background-color: #ff4500; color: white;">
                                <i class="fas fa-shopping-cart me-2"></i>ADD TO CART
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php
        }
        
    } else {
        echo "<p class='text-center text-muted'>No products available yet.</p>";
    }
    ?>
</div><br><br><br>
<div class="honcho">
  <h3>HONCHO'S FITNESS AND WELLNESS CENTRE</h3><br><br>
  <h1>GRIND TO GREATNESS AND PRESTIGE</h1><br><br>
  <h3>BUILT ON THE BELIEF THAT EVERYONE REGARDLESS OF BACKGROUND OR</h3>
  <h3>EXPERIENCE DESERVES ACCESS TO WORLD-CLASS TRAINING AND A SUPPORTING</h3>
  <h3>COMMUNITY</h3><br><br>
  <p>Chimaobi Gideon(HONCHO GTR)</p><br>
  <h3>FOUNDER,HONCHO'S FITNESS</h3>
 </diV>
 <article>
 <div class="trusted">
  <h1>TRUSTED BY FITNESS ENTHUSIASTS &</h1>
  <h1>BUISNESS OWNERS ACROSS NIGERIA</h1><br>
  <p>We have supplied top-tier gym and fitness equipment to thousands of satisfied customers, including commercial</p>
  <p>gyms, corporate wellness programs, schools, hotels, and individuals building home gyms.</p>
 </div><br><br>
 <div class="reviews container">
    <div class="row justify-content-center g-4">
        <div class="nani col-md-4">
            <h3>Nani Strong</h3><br>
            <p>Managing Director:Commercial Gym</p><br>
            <p>⭐⭐⭐⭐⭐</p><br><br>
            <p>Honcho's gym established our Lagos fitness center, a fully equipped training facility featuring modern strength and cardio machines.</p>
        </div>
        <div class="henry col-md-4">
            <h3>Henry umaego</h3><br>
            <p>Manager:Home Gym</p><br>
            <p>⭐⭐⭐⭐⭐</p><br><br>
            <p>Honcho's gym assisted me in creating a personalized home workout space for fitness enthusiasts, equipped with high-quality gears</p>
        </div>
        <div class="victor col-md-4">
            <h3>Victor Gucci</h3><br>
            <p>Developer of recreational centre</p><br>
            <p>⭐⭐⭐⭐⭐</p><br><br>
            <p>BodyFit helped us supply a family entertainment hub with sports and play equipment, enhancing customer engagement.</p>
        </div>
    </div>
 </div>
 </article>
 <main>
 <p style="font-size: 30px;">In the gym and in life, effort speaks louder than words.</p>
 <P style="font-size: 20px;">Show up, put in the work, and let your results do the talking. The grind never lies!</P><br><br>
 <p>at <strong style="color: whitesmoke;">HONCHO'S FITNESS AND WELLNESS CENTRE(Honcho's gym) </strong>, we are your companion, always, forever.</p>
 </main>
 <div class="ending container">
    <div class="row justify-content-between">
        <div class="col-md-4 mb-4">
            <h3>HONCHO'S GYM</h3><br><br>
            <p>We are the best when it comes</p>
            <p>to revolutionizing fitness and</p>
            <p>strength,come let help build a </p>
            <p>better you</p>
        </div>
        <div class="col-md-4 mb-4">
            <h3>QUICK LINKS</h3><br><br>
            <div class="qlinks">
                <a href="#">Home</a><br>
                <a href="#">About Us</a><br>
                <a href="#">Best Sellers</a><br>
                <a href="#">Contact Us</a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <h3>CONTACT US</h3><br><br>
            <p><i class="fa-solid fa-mobile-button"></i> (+2348021333092)</p><br>
            <p><i class="fa-regular fa-envelope"></i>info@honcho'sgym.com</p><br>
            <p><i class="fa-solid fa-location-dot"></i>25 awolowo way,ikoyi</p>
        </div>
    </div>
 </div>
 <footer>
  <p>
    Copyright &copy; 2025 - Honcho's gym(created by Honcho) All right reserved
  </p>
 </footer>

 <a href="#top" id="back-to-top" class="btn btn-danger rounded-circle p-3" style="display:none;" aria-label="Back to Top">
        <i class="fa-solid fa-arrow-up"></i>
    </a>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        window.onscroll = function() {
            var button = document.getElementById('back-to-top');
            // Show button after scrolling 500px down
            if (document.body.scrollTop > 500 || document.documentElement.scrollTop > 500) {
                button.style.display = "block";
            } else {
                button.style.display = "none";
            }
        };
    </script>
    <?php
     include  'toast_handler.php';
     ?>
</body>
</html>