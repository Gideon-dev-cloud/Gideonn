<?php
session_start();
require_once 'db_config.php';

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST["submit"])) {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email)) {
        header("Location: login.php?error=empty_email");
        exit;
    }

    if (empty($password)) {
        header("Location: login.php?error=empty_password");
        exit;
    }

    // FIX 1: We select 'is_admin' from the database
    $sql = "SELECT id, first_name, last_name, password_hash, is_admin FROM users WHERE email = ?";

    if ($stmt = $conn->prepare($sql)) {

        $stmt->bind_param("s", $email);

        if (!$stmt->execute()) {
            $_SESSION["login_error"] = "Query failed";
            header("Location: login.php?error=database");
            exit;
        }

        // FIX 2: We bind the result to $isAdmin
        $stmt->bind_result($id, $firstName, $lastName, $passwordHash, $isAdmin);

        if ($stmt->fetch()) {

            if (password_verify($password, $passwordHash)) {

                // Prevent session fixation attacks
                session_regenerate_id(true);

                $_SESSION["loggedin"] = true;
                $_SESSION["id"] = $id;
                $_SESSION["name"] = $firstName . " " . $lastName;
                
                // FIX 3: Store the admin status in the session so admin_check.php can see it
                $_SESSION["is_admin"] = (int)$isAdmin;

                // FIX 4: Check if they are an admin and route them to the correct dashboard
                if ($_SESSION["is_admin"] === 1) {
                    header("Location: admin_dashboard.php");
                } else {
                    header("Location: dashboard.php");
                }
                exit;

            } else {
                header("Location: login.php?error=invalid_password");
                exit;
            }

        } else {
            header("Location: login.php?error=no_account");
            exit;
        }

        $stmt->close();

    } else {
        $_SESSION["login_error"] = "Prepare failed";
        header("Location: login.php?error=database");
        exit;
    }
}

$conn->close();
?>