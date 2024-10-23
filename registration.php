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
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $checkQuery = "SELECT * FROM registration WHERE email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        // Email already registered
        echo "Email already registered.Try a different one";
    } else {
        // Prepare SQL statement
        $sql = "INSERT INTO registration (first_name, second_name, email, password)
                VALUES ('$firstName', '$lastName', '$email', '$hashedPassword')";

        // Execute SQL statement
        if ($conn->query($sql) === TRUE) {
            // Display registration success message
            echo "Registration successful. Please go back to log in";
            exit(); // Ensure script stops executing after displaying the message
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Close connection
$conn->close();
?>
