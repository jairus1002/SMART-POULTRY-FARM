<?php
// Database connection details
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

// Query to fetch feeding expenses, vaccination expenses, infrastructure expenses, and total revenue
$sql = "SELECT feed_expe, vaccination_expe, ifra_expe, total_expe FROM finance";
$result = $conn->query($sql);

// Check if the query was successful
if ($result === false) {
    // Print out the SQL error for debugging
    die("Error: " . $conn->error);
}

// Initialize arrays to store data for the graphs
$feedExpenses = [];
$vaccinationExpenses = [];
$infrastructureExpenses = [];
$totalExpenses = [];

if ($result->num_rows > 0) {
    // Fetch data and store it in arrays
    while ($row = $result->fetch_assoc()) {
        $feedExpenses[] = $row['feed_expe'];
        $vaccinationExpenses[] = $row['vaccination_expe'];
        $infrastructureExpenses[] = $row['ifra_expe'];
        $totalExpenses[] = $row['total_expe'];
    }
} else {
    // No data found, optionally handle this case
    echo "No records found";
}

// Prepare the data to be returned as JSON
$response = [
    "feedExpenses" => $feedExpenses,
    "vaccinationExpenses" => $vaccinationExpenses,
    "infrastructureExpenses" => $infrastructureExpenses,
    "totalExpenses" => $totalExpenses
];

// Set the content type to JSON
header('Content-Type: application/json');

// Return the JSON data
echo json_encode($response);

// Close the database connection
$conn->close();
?>
