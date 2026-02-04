<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honcho's GYM | Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #0d0d0d;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: Arial, Helvetica, sans-serif;
        }
        .login-card {
            background-color: #1a1a1a;
            border: 1px solid orangered;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 69, 0, 0.4);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem;
        }
        .form-control:focus {
            border-color: orangered;
            box-shadow: 0 0 0 0.25rem rgba(255, 69, 0, 0.5);
            background-color: #252525;
            color: white;
        }
        .form-control {
            background-color: #252525;
            border: 1px solid #333;
            color: white;
            border-right: none; /* Remove right border for merge with eye icon */
        }
        
        /* Style for the reveal button */
        .btn-reveal {
            background-color: #252525;
            border: 1px solid #333;
            border-left: none; /* Remove left border to merge with input */
            color: #6c757d;
        }
        .btn-reveal:hover, .btn-reveal:focus {
            background-color: #333;
            color: orangered;
            border-color: #333;
        }

        .btn-login {
            background-color: orangered;
            border-color: orangered;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-login:hover {
            background-color: #cc4700;
            border-color: #cc4700;
        }
        .gym-logo {
            width: 60px;
            margin-bottom: 1rem;
        }
        .card-header h3 {
            color: orangered;
            font-weight: 700;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center">
            <img src="./img/barbell.png" alt="Honcho's Gym Logo" class="gym-logo">
            <div class="card-header border-0 mb-4">
                <h3>MEMBER LOGIN</h3>
                <p class="text-secondary">Access your personalized fitness dashboard.</p>
            </div>
        </div>
        
        <form action="auth_login.php" method="POST">
            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger text-center p-2 small">
                    <?php 
                        if($_GET['error'] == 'invalid_password') echo "Incorrect password.";
                        elseif($_GET['error'] == 'no_account') echo "No account found.";
                    ?>
                </div>
            <?php endif; ?>

            <div class="mb-3">
                <label for="emailInput" class="form-label visually">Email address</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-user"></i></span>
                    <input type="email" class="form-control" id="emailInput" name="email" placeholder="Email or Username" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="passwordInput" class="form-label visually">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-dark border-secondary text-white-50"><i class="fas fa-lock"></i></span>
                    <input type="password" class="form-control" id="passwordInput" name="password" placeholder="Password" required>
                    <button class="btn btn-reveal" type="button" id="togglePassword">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
            
            <div class="d-grid mb-3">
                <button type="submit" name="submit" class="btn btn-login btn-lg">GRIND & LOGIN</button>
            </div>
            
            <div class="text-center">
                <a href="#" class="text-danger small text-decoration-none">Forgot Password?</a>
                <div class="mt-3">
                    <span class="text-secondary">New Member? </span><a href="register.php" class="text-white small text-decoration-none">Sign Up Here</a>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#passwordInput');
        const icon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function (e) {
            // toggle the type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // toggle the eye / eye slash icon
            if (type === 'text') {
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    </script>
</body>
</html>