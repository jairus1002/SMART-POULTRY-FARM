<?php
// Database connection variables
$servername = "localhost"; // Replace with your server name
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "poultry2"; // Replace with your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize success and error variables
$success = "";
$error = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize and retrieve form inputs
    $disease = $conn->real_escape_string(trim($_POST['disease_name']));
    $description = $conn->real_escape_string(trim($_POST['description']));
    $severity_level = $conn->real_escape_string(trim($_POST['severity']));
    $location = $conn->real_escape_string(trim($_POST['location']));
    $day_outbreak = $conn->real_escape_string(trim($_POST['date_of_outbreak']));
    $contact = $conn->real_escape_string(trim($_POST['contact_info']));

    // Prepare SQL query to insert data
    $sql = "INSERT INTO alert2 (disease, description, security_level, location, day_outbreak, contact) 
            VALUES ('$disease', '$description', '$severity_level', '$location', '$day_outbreak', '$contact')";

    // Execute the query and check for success
    if ($conn->query($sql) === TRUE) {
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
        $error = "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
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
            color: #3b82f6; /* Blue for titles */
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

        <a href="expertalert2.html" class="btn btn-primary btn-lg btn-block">Submit Another Alert</a>
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

