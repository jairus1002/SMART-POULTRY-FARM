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

// Initialize totals
$totalFeedExpenses = 0;
$totalVaccinationExpenses = 0;
$totalInfrastructureExpenses = 0;
$totalOtherExpenses = 0;
$totalRevenue = 0;

// Query to get expense data
$sqlExpenses = "SELECT feed_expe, vaccination_expe, infra_expe, addition_expe FROM finance";
$resultExpenses = $conn->query($sqlExpenses);

if ($resultExpenses && $resultExpenses->num_rows > 0) {
    // Calculate total expenses
    while ($row = $resultExpenses->fetch_assoc()) {
        $totalFeedExpenses += $row['feed_expe'];
        $totalVaccinationExpenses += $row['vaccination_expe'];
        $totalInfrastructureExpenses += $row['infra_expe'];
        $totalOtherExpenses += $row['addition_expe'];
    }
} else {
    // No expenses found, set to 0
    $totalFeedExpenses = $totalVaccinationExpenses = $totalInfrastructureExpenses = $totalOtherExpenses = 0;
}

// Query to get total revenue (Make sure the column name matches your database)
$sqlRevenue = "SELECT total_revenue FROM finance";  // Changed to total_revenue if that's correct
$resultRevenue = $conn->query($sqlRevenue);

if ($resultRevenue && $resultRevenue->num_rows > 0) {
    // Sum all total_revenue values for revenue
    while ($row = $resultRevenue->fetch_assoc()) {
        $totalRevenue += $row['total_revenue'];  // Changed to total_revenue if that's correct
    }
} else {
    // No revenue found, set to 0
    $totalRevenue = 0;
}

// Calculating profits
$totalExpenses = $totalFeedExpenses + $totalVaccinationExpenses + $totalInfrastructureExpenses + $totalOtherExpenses;
$grossProfit = $totalRevenue - $totalExpenses;
$netProfit = $grossProfit > 0 ? $grossProfit : 0;
$losses = $grossProfit < 0 ? abs($grossProfit) : 0;

// Prepare the data to be returned as JSON
$response = [
    "grossProfit" => $grossProfit,
    "netProfit" => $netProfit,
    "losses" => $losses
];

// Set the content type to JSON
header('Content-Type: application/json');

// Return the JSON data
echo json_encode($response);

// Close the database connection
$conn->close();
?>
