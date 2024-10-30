<?php
// Start a session to manage logged-in users
session_start();

// Database connection parameters
$host = 'localhost';  // Update if necessary
$dbname = 'poultry2';  // Replace with your database name
$user = 'root';  // Replace with your database username
$pass = '';  // Replace with your database password

// Establish database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect and sanitize input
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);

    try {
        // Prepare SQL query to fetch the user with the entered email
        $sql = "SELECT * FROM expert_registration WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        // Check if the user exists
        if ($stmt->rowCount() === 1) {
            $expert = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify the password
            if (password_verify($password, $expert['password'])) {
                // Store user information in session variables
                $_SESSION['expert_id'] = $expert['id'];
                $_SESSION['full_name'] = $expert['full_names'];

                // Redirect to the expert dashboard or homepage
                header("Location: expertalert2.html");
                exit;
            } else {
                // Invalid password
                $error = "Invalid email or password. Please try again.";
            }
        } else {
            // User not found
            $error = "Invalid email or password. Please try again.";
        }
    } catch (PDOException $e) {
        die("Database error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Login - Poultry Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('chick7.jpg'); /* Replace with relevant background */
            background-size: cover;
            background-position: center;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-container {
            background-color: rgba(0, 0, 0, 0.85);
            padding: 40px;
            border-radius: 15px;
            width: 100%;
            max-width: 400px;
        }
        h1 {
            color: #17a2b8;
            margin-bottom: 30px;
        }
        .alert {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #17a2b8;
            border: none;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Expert Login</h1>
        <!-- Display error message if login fails -->
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="expertlogin.php" method="POST">
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Enter Email Address" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Enter Password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">Login</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
