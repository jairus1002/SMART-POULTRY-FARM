<?php
session_start(); // Start session for login state

// Enable error reporting for debugging during development (optional, disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection credentials
$host = "localhost";
$dbname = "poultry2";
$username = "root";
$password = "";

// Establish the database connection
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("<p>Connection failed: " . $e->getMessage() . "</p>");
}

// Initialize error message variable
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password_input = $_POST['password'];

    // Prepare a SQL statement to prevent SQL injection
    try {
        $stmt = $conn->prepare("SELECT * FROM registration WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if the user exists
        if ($stmt->rowCount() > 0) {
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $hashed_password = $user['password']; // Get the hashed password

            // Verify the entered password with the hashed password
            if (password_verify($password_input, $hashed_password)) {
                $_SESSION['user_id'] = $user['id']; // Store user ID in session
                header("Location: expertpage1.html"); // Redirect to dashboard
                exit();
            } else {
                $error = "Invalid password. Please try again."; // Error for incorrect password
            }
        } else {
            $error = "No account found with that email."; // Error for non-existing email
        }
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage(); // Handle SQL errors
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Poultry Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8d7da; /* Light red background for error */
            color: #721c24; /* Dark red color for text */
        }
        .container {
            margin-top: 100px; /* Center the alert in the middle of the page */
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <div class="alert alert-danger">
            <h4 class="alert-heading">Error!</h4>
            <p><?php if (isset($error)) echo $error; ?></p>
            <hr>
            <p class="mb-0">Please check your email and password, then try logging in again.</p>
        </div>
        <a href="farmerlogin.html" class="btn btn-primary">Back to Login</a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
