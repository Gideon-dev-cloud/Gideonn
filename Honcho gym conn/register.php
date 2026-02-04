<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Honcho's GYM | Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            background-color: #0d0d0d;
            color: white;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            padding: 20px 0;
            font-family: Arial, Helvetica, sans-serif;
        }
        .register-card {
            background-color: #1a1a1a;
            border: 1px solid orangered;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(255, 69, 0, 0.4);
            width: 100%;
            max-width: 600px;
            padding: 2.5rem;
            margin-top: 20px;
        }
        .form-control:focus, .form-select:focus {
            border-color: orangered;
            box-shadow: 0 0 0 0.25rem rgba(255, 69, 0, 0.5);
            background-color: #252525;
            color: white;
        }
        .form-control, .form-select {
            background-color: #252525;
            border: 1px solid #333;
            color: white;
        }
        
        /* NEW STYLES FOR SHOW PASSWORD FEATURE */
        .reveal-input {
            border-right: none; /* Merges input with the eye button */
        }
        .btn-reveal {
            background-color: #252525;
            border: 1px solid #333;
            border-left: none; /* Merges button with the input */
            color: #6c757d;
        }
        .btn-reveal:hover, .btn-reveal:focus {
            background-color: #333;
            color: orangered;
            border-color: #333;
        }

        .form-control::placeholder {
            color: #ccc;
        }
        .form-select option {
            background-color: #1a1a1a;
        }
        .btn-register {
            background-color: orangered;
            border-color: orangered;
            color: white;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-register:hover {
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
        .section-heading {
            color: #ccc;
            border-bottom: 1px solid #333;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <div class="text-center">
            <img src="./img/barbell.png" alt="Honcho's Gym Logo" class="gym-logo">
            <div class="card-header border-0 mb-4">
                <h3>CREATE YOUR HONCHO'S ACCOUNT</h3>
                <p class="text-secondary">Start your journey to greatness with us!</p>
            </div>
        </div>
        
        <form action="register_process.php" method="post">
            <h4 class="section-heading">Account Information</h4>
            <div class="mb-3">
                <label for="regEmail" class="form-label">Email address</label>
                <input type="email" name="regEmail" class="form-control" id="regEmail" placeholder="you@example.com" required>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="regPassword" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" name="regPassword" class="form-control reveal-input" id="regPassword" placeholder="Min 8 chars" required>
                        <button class="btn btn-reveal" type="button" id="toggleRegPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <div class="input-group">
                        <input type="password" name="confirmPassword" class="form-control reveal-input" id="confirmPassword" required>
                        <button class="btn btn-reveal" type="button" id="toggleConfirmPassword">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>

            <h4 class="section-heading">Personal Details</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="firstName"  class="form-label">First Name</label>
                    <input type="text" name="firstName" class="form-control" id="firstName" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lastName" class="form-label">Last Name</label>
                    <input type="text" name="lastName" class="form-control" id="lastName" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="tel" name="phone" class="form-control" id="phone" placeholder="+234 80..." required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" name="gender" id="gender" required>
                        <option value="" disabled selected>Select...</option>
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="birthDate" class="form-label">Date of Birth</label>
                    <input type="date" name="birthDate" class="form-control" id="birthDate" required>
                </div>
            </div>

            <h4 class="section-heading">Fitness Profile</h4>
            <div class="mb-3">
                <label for="goal" class="form-label">Primary Fitness Goal</label>
                <select class="form-select" name="goal" id="goal" required>
                    <option value="" disabled selected>Select your main goal...</option>
                    <option value="muscle">Build Muscle / Strength</option>
                    <option value="weightloss">Weight Loss / Fat Burning</option>
                    <option value="endurance">Improve Endurance / Cardio</option>
                    <option value="general">General Wellness / Health</option>
                </select>
            </div>
            
            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="terms" required>
                <label class="form-check-label" for="terms">I agree to the <a href="#" class="text-danger">Terms and Conditions</a></label>
            </div>

            <div class="d-grid mb-3">
                <button type="submit" class="btn btn-register btn-lg">COMPLETE SIGN UP</button>
            </div>
            
            <div class="text-center">
                <span class="text-secondary">Already have an account? </span><a href="login.php" class="text-white small text-decoration-none">Login Here</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script>
        function setupToggle(buttonId, inputId) {
            const toggle = document.querySelector(buttonId);
            const passwordField = document.querySelector(inputId);
            const icon = toggle.querySelector('i');

            toggle.addEventListener('click', function () {
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                
                // Toggle eye icon
                icon.classList.toggle('fa-eye');
                icon.classList.toggle('fa-eye-slash');
            });
        }

        // Initialize both toggles
        setupToggle('#toggleRegPassword', '#regPassword');
        setupToggle('#toggleConfirmPassword', '#confirmPassword');
    </script>
</body>
</html>