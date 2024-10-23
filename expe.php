<?php
// Database connection details
$servername = "localhost"; // Change if different
$username = "root"; // Your database username
$password = ""; // Your database password
$dbname = "poultry2"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    echo "Connected successfully to the databaseeee.<br>";
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $feed_expe = $_POST['feed_expe'];
    $vaccination_expe = $_POST['vaccination_expe'];
    $infra_expe = $_POST['infra_feed']; // Corrected variable name
    $addition_expe = $_POST['addition_expe'];

    // Calculate total expenses
    $total_expe = $feed_expe + $vaccination_expe + $infra_expe + $addition_expe;

    // Prepare the SQL statement to insert all data
    $sql = "INSERT INTO finance (feed_expe, vaccination_expe, ifra_expe, addition_expe, total_expe, datetime)
            VALUES (?, ?, ?, ?, ?, NOW())"; // Adding the current timestamp
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        // Error preparing the statement
        die("Error preparing the SQL statement: " . $conn->error);
    }

    // Bind the parameters
    $stmt->bind_param("iiiii", $feed_expe, $vaccination_expe, $infra_expe, $addition_expe, $total_expe);

    // Execute the statement
    if ($stmt->execute()) {
        // Show a success icon and message
        echo '<div id="successMessage" style="display: block; margin-top: 20px; color: green; font-size: 18px;">
                <span>&#10004;</span> All data inserted successfully.
              </div>';
    } else {
        // Show error message
        echo '<div style="color: red; font-size: 18px;">
                Error executing the query: ' . $stmt->error . '
              </div>';
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
