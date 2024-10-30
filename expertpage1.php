<?php
session_start(); // Start session

// Enable error reporting for debugging (optional, disable in production)
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

// Initialize variables for error and success messages
$error = "";
$success = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and validate inputs
    $disease_name = trim($_POST['disease_name']);
    $description = trim($_POST['description']);
    $location = trim($_POST['location']);
    $date_of_outbreak = $_POST['date_of_outbreak'];
    $contact_info = trim($_POST['contact_info']);

    // Validate required fields
    if (empty($disease_name) || empty($description) || empty($location) || empty($date_of_outbreak)) {
        $error = "Please fill in all required fields.";
    } else {
        // Prepare a SQL statement to prevent SQL injection
        try {
            $stmt = $conn->prepare(
                "INSERT INTO alert (disease, description, location, date, contact) 
                 VALUES (:disease, :description, :location, :date, :contact)"
            );
            $stmt->bindParam(':disease', $disease_name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':date', $date_of_outbreak);
            $stmt->bindParam(':contact', $contact_info);

            // Execute the statement
            if ($stmt->execute()) {
                $success = "Alert submitted successfully!";

                // Execute the Python script
                $output = shell_exec("python C:\\xampp\\htdocs\\PROJECT_2\\sendalert.py 2>&1");

                // Check the result of the Python script
                if ($output) {
                    $success .= "<br>SMS alerts sent successfully.";
                } else {
                    $error = "Failed to send SMS alerts.";
                }
            } else {
                $error = "Error submitting alert. Please try again.";
            }
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alert Submission - Poultry Management System</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #e0e7ff; /* Soft light blue background */
            color: #333; /* Dark color for text */
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Full viewport height */
            margin: 0;
        }

        .container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #3b82f6; /* Bright blue for titles */
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5rem;
            font-weight: 600;
            text-shadow: 0 2px 5px rgba(0, 59, 168, 0.3);
        }

        .alert-container {
            margin-top: 20px;
        }

        .loading-container {
            display: none; /* Initially hidden */
            text-align: center;
            margin-top: 20px;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }

        .btn {
            margin-top: 20px;
            transition: background-color 0.3s, transform 0.3s;
            border-radius: 20px; /* Rounded button */
        }

        .btn-primary {
            background-color: #3b82f6; /* Bright blue */
            border: none;
        }

        .btn-primary:hover {
            background-color: #2563eb; /* Darker blue */
            transform: translateY(-2px);
        }

        .btn-secondary {
            background-color: #6b7280; /* Gray */
            border: none;
        }

        .btn-secondary:hover {
            background-color: #4b5563; /* Darker gray */
            transform: translateY(-2px);
        }

        @media (max-width: 576px) {
            h1 {
                font-size: 2rem; /* Smaller title on small screens */
            }
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="text-center">
            <h1>Alert Submission Result</h1>
        </div>

        <div class="loading-container" id="loading">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
            </div>
            <p>Processing your submission...</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-container">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-success alert-container">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <a href="expertpage1.html" class="btn btn-primary btn-lg btn-block">Submit Another Alert</a>
        <a href="alert.html" class="btn btn-secondary btn-lg btn-block">Back to Dashboard</a>
    </div>

    <script>
        // Show loading animation on page load
        document.addEventListener("DOMContentLoaded", function() {
            var loadingDiv = document.getElementById('loading');
            loadingDiv.style.display = 'block'; // Show loading
            setTimeout(function() {
                loadingDiv.style.display = 'none'; // Hide loading after 3 seconds
            }, 3000); // Adjust this time as needed
        });
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

