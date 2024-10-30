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

// Retrieve all phone numbers from the registration table
$phoneQuery = "SELECT phone_number FROM registration";
$phoneResult = $conn->query($phoneQuery);
$phoneNumbers = [];

if ($phoneResult->num_rows > 0) {
    while ($row = $phoneResult->fetch_assoc()) {
        $phoneNumbers[] = $row['phone_number'];
    }
} else {
    echo json_encode(["error" => "No phone numbers found."]);
    exit();
}

// Retrieve the latest alert from the alert table
$alertQuery1 = "SELECT * FROM alert ORDER BY date DESC LIMIT 1";
$alertResult1 = $conn->query($alertQuery1);
$latestAlert1 = $alertResult1->fetch_assoc();

// Retrieve the latest alert from the alert2 table
$alertQuery2 = "SELECT * FROM alert2 ORDER BY day_outbreak DESC LIMIT 1";
$alertResult2 = $conn->query($alertQuery2);
$latestAlert2 = $alertResult2->fetch_assoc();

// Prepare the alerts to send
$alertMessage1 = isset($latestAlert1) ? 
    "ALERT 1: " . $latestAlert1['disease'] . " - " . $latestAlert1['description'] : "No alert 1 available.";
$alertMessage2 = isset($latestAlert2) ? 
    "ALERT 2: " . $latestAlert2['disease'] . " - " . $latestAlert2['description'] : "No alert 2 available.";

// Send data as a JSON response
$response = [
    "phoneNumbers" => $phoneNumbers,
    "messages" => [$alertMessage1, $alertMessage2]
];

echo json_encode($response);

// Close the connection
$conn->close();
?>
