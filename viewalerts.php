<?php
// Database connection details
$host = "localhost";
$user = "root";
$password = "";
$dbname = "poultry2";

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// SQL query to fetch the latest 3 alerts
$sql = "SELECT disease, description, location, date, contact 
        FROM alert
        ORDER BY date DESC 
        LIMIT 3";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . $conn->error);
}

// Prepare response
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '
        <div class="alert-card">
            <div class="alert-title">' . htmlspecialchars($row["disease"]) . '</div>
            <div class="alert-content">' . htmlspecialchars($row["description"]) . '</div>
            <div class="alert-info">
                <p><strong>Location:</strong> ' . htmlspecialchars($row["location"]) . '</p>
                <p><strong>Date:</strong> ' . htmlspecialchars($row["date"]) . '</p>
                <p><strong>Contact:</strong> ' . htmlspecialchars($row["contact"]) . '</p>
            </div>
        </div>';
    }
} else {
    echo '<p>No alerts found.</p>';
}

// Close the connection
$conn->close();
?>
