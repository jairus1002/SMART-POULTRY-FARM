<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "poultry2";

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
    $phoneNumber = $_POST["phoneNumber"];  // Correct assignment

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Check if the email already exists in the database
    $checkQuery = "SELECT * FROM registration WHERE email='$email'";
    $result = $conn->query($checkQuery);

    if ($result->num_rows > 0) {
        echo "Email already registered. Try a different one.";
    } else {
        // Prepare SQL statement with correct phone number variable
        $sql = "INSERT INTO registration (first_name, second_name, email, password, phone_number)
                VALUES ('$firstName', '$lastName', '$email', '$hashedPassword', '$phoneNumber')";

// Execute SQL statement
if ($conn->query($sql) === TRUE) {
    echo "Registration successful. Please go back to log in.";
    exit();
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
        
    }
}

// Close connection
$conn->close();
?>
