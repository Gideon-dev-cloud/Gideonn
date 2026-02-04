<?php
// 1. Include the database configuration file
require_once 'db_config.php';

// Check if the form was submitted using POST method
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 2. Validate and sanitize input data
    $email = trim($_POST['regEmail']);
    $password = $_POST['regPassword'];
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $phone = trim($_POST['phone']);
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthDate'];
    $goal = $_POST['goal'];

    // Basic required field check
    if (empty($email) || empty($password) || empty($firstName) || empty($lastName)) {
        header("location: register.html?error=missing_fields");
        exit;
    }

    // 3. Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // 4. Prepare an INSERT statement for security (Prepared Statements)
    // $sql = "INSERT INTO users (first_name, last_name, email, password_hash, phone, gender, birth_date, fitness_goal) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    // if ($stmt = $conn->prepare($sql)) {
    //     // Bind parameters to the statement
    //     $stmt->bind_param("ssssssss", $param_fn, $param_ln, $param_email, $param_hash, $param_phone, $param_gender, $param_bdate, $param_goal);

        // Set parameters
        // $param_fn = $firstName;
        // $param_ln = $lastName;
        // $param_email = $email;
        // $param_hash = $hashed_password;
        // $param_phone = $phone;
        // $param_gender = $gender;
        // $param_bdate = $birthDate;
        // $param_goal = $goal;

        // // 5. Execute the statement
        // if ($stmt->execute()) {
            // Success! Redirect to login page

         $sql  = "INSERT INTO users (first_name, last_name, email, password_hash, phone, gender, birth_date, fitness_goal) VALUES ('$firstName', '$lastName', '$email', '$hashed_password', '$phone', '$gender', '$birthdate', '$goal')";

if ($conn->query($sql)) {
    echo "Gym Registration Successful";
}
else
  echo  "Not Sucessful".$conn->error;
}

//             header("location: login.php?success=registered");
//             exit;
//         } else {
//             // Check for duplicate email error
//              if ($conn->errno == 1062) {
//                 echo "ERROR: This email address is already registered.";
//             } else {
//                 echo "Something went wrong. Please try again later. Error: " . $stmt->error;
//             }
//         }

//         // Close statement
//         $stmt->close();
//     } else {
//         echo "ERROR: Could not prepare statement.";
//     }

//     // Close connection
//     $conn->close();
// }
// ?>