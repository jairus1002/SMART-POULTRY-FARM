<?php
// Database connection parameters
$host = 'localhost';  // Replace if necessary
$dbname = 'poultry2';  // Replace with your database name
$user = 'root';  // Replace with your database username
$pass = '';  // Replace with your database password

// Establishing database connection
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Database connection successful!<br>";  // Debug printout
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect and sanitize inputs
    $full_name = htmlspecialchars(trim($_POST['full_name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $expertise = htmlspecialchars(trim($_POST['expertise_area']));
    $qualification = htmlspecialchars(trim($_POST['qualification']));
    $address = htmlspecialchars(trim($_POST['address']));
    $access_key = trim($_POST['access_key']);

    // Debug printouts for input values
    echo "Full Name: $full_name<br>";
    echo "Email: $email<br>";
    echo "Phone: $phone<br>";
    echo "Expertise: $expertise<br>";
    echo "Qualification: $qualification<br>";
    echo "Address: $address<br>";
    echo "Access Key: $access_key<br>";

    // Validate access key
    $validKey = "12345";  // Replace with your actual key
    if ($access_key !== $validKey) {
        echo "<script>alert('Invalid access key! Please try again.'); window.history.back();</script>";
        exit;
    }

    // Hash the password securely
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    try {
        // Prepare SQL query
        $sql = "INSERT INTO expert_registration (full_names, email, password, phone_number, expertise, qualification, address, access_key) 
                VALUES (:full_name, :email, :password, :phone, :expertise, :qualification, :address, :access_key)";

        $stmt = $pdo->prepare($sql);

        // Bind parameters to prevent SQL injection
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':expertise', $expertise);
        $stmt->bindParam(':qualification', $qualification);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':access_key', $access_key);

        // Execute query and provide feedback
        if ($stmt->execute()) {
            echo "SQL Query executed successfully!<br>";  // Debug printout
            echo "<script>alert('Registration successful!'); window.location.href='expertlogin.html';</script>";
        } else {
            echo "SQL Query failed.<br>";  // Debug printout
            echo "<script>alert('Registration failed. Please try again.'); window.history.back();</script>";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}
?>
