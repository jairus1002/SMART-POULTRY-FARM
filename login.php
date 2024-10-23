<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = ""; // Your MySQL password
$dbname = "poultry2"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Prepare SQL statement to retrieve user data by email
    $checkQuery = "SELECT * FROM registration WHERE email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Email found in database, verify password
        $row = $result->fetch_assoc();
        $hashedPassword = $row["password"];

        // Verify password
        if (password_verify($password, $hashedPassword)) {
            // Password matches, login successful
            echo "Login successful!";
            exit();
        } else {
            // Password doesn't match
            echo "Incorrect password!";
        }
    } else {
        // Email not found in database
        echo "Email not registered!";
    }
}

// Close connection
$conn->close();
?>
