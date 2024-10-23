<?php
/// Database connection parameters
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
    // Retrieve data from the remind table
    $sql = "SELECT hatching_date, poultry_type, action FROM remind ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch the last row
        $row = $result->fetch_assoc();
        $hatchDate = $row["hatching_date"];
        $poultryType = $row["poultry_type"];
        $action = $row["action"];

        // Initialize feeding schedule array with default values
        $feedingSchedule = array(
            'starter_feed' => "",
            'grower_feed' => "",
            'layer_feed' => "" // For layers
        );

        switch ($action) {
            case "management":
                // Check if the type of poultry is layers
                if ($poultryType === "layers") {
                    // Calculate dates for feeding program for layers
                    // Modify the calculation to start from the retrieved hatching date
                    $hatchDate = strtotime($hatchDate); // Convert to Unix timestamp
                    $feedingSchedule['starter_feed'] = date('Y-m-d', strtotime('+0 weeks', $hatchDate));
                    $feedingSchedule['grower_feed'] = date('Y-m-d', strtotime('+0 weeks', $hatchDate));
                    // Set layer feed to "Not applicable" as it is not provided in the form
                    $feedingSchedule['layer_feed'] = "Not applicable";
        
                    
        
                    // Insert calculated feeding schedule into layers1 table
                    $insertSql = "INSERT INTO layers2 (starter, grower, layer) 
                                  VALUES ('$feedingSchedule[starter_feed]', '$feedingSchedule[grower_feed]', '$feedingSchedule[layer_feed]')";
                    if ($conn->query($insertSql) === TRUE) {
                        echo "Corfimation success.";
                    } else {
                        echo "Error: " . $insertSql . "<br>" . $conn->error;
                    }
                } elseif ($poultryType === "broilers") {
                    // Calculate dates for feeding program for broilers
                    // Modify the calculation to start from the retrieved hatching date
                    $hatchDate = strtotime($hatchDate); // Convert to Unix timestamp
                    $feedingSchedule['starter_feed'] = date('Y-m-d', strtotime('+0 weeks', $hatchDate));
                    $feedingSchedule['grower_feed'] = date('Y-m-d', strtotime('+0 weeks', $hatchDate));
                    $feedingSchedule['finisher_feed'] = date('Y-m-d', strtotime('+0 weeks', $hatchDate));
        
                    // Echo the calculated dates
                    echo "Starter Feed Date: " . $feedingSchedule['starter_feed'] . "<br>";
                    echo "Grower Feed Date: " . $feedingSchedule['grower_feed'] . "<br>";
                    echo "Finisher Feed Date: " . $feedingSchedule['finisher_feed'] . "<br>";
        
                    // Insert calculated feeding schedule into broiler1 table
                    $insertSql = "INSERT INTO broiler1 (starterb, growerb, finisher) 
                                  VALUES ('$feedingSchedule[starter_feed]', '$feedingSchedule[grower_feed]', '$feedingSchedule[finisher_feed]')";
                    if ($conn->query($insertSql) === TRUE) {
                        echo "Press finish button to finalise";
                    } else {
                        echo "Error: " . $insertSql . "<br>" . $conn->error;
                    }
                }
                
                case "vaccination":
                    // Handle vaccination action for layers
                    if ($poultryType === "layers") {
                        // Calculate vaccination dates for layers
                        // Modify the calculation to start from the retrieved hatching date
                        $hatchDate = strtotime($hatchDate); // Convert to Unix timestamp
                        $vaccinationScheduleLayers = array(
                            'marek' => date('Y-m-d', $hatchDate), // Day 1: Marek's disease
                            'newcastle_1' => date('Y-m-d', strtotime('+2 weeks', $hatchDate)), // Week 2: Newcastle disease (1st dose)
                            'bronchitis_1' => date('Y-m-d', strtotime('+4 weeks', $hatchDate)), // Week 4: Infectious bronchitis (1st dose)
                            'newcastle_2' => date('Y-m-d', strtotime('+6 weeks', $hatchDate)), // Week 6: Newcastle disease (2nd dose)
                            'bronchitis_2' => date('Y-m-d', strtotime('+8 weeks', $hatchDate)), // Week 8: Infectious bronchitis (2nd dose)
                            'fowl_pox' => date('Y-m-d', strtotime('+12 weeks', $hatchDate)) // Week 12: Fowl pox (if required)
                        );
                    
                        // Insert calculated vaccination schedule into vaccination_l table
                        $insertSql = "INSERT INTO vaccination_b (Marek, Newcastle1, bronchitis1, Newcastle2, bronchitis2, Fowl_pox) 
                                      VALUES (
                                          '$vaccinationScheduleLayers[marek]', 
                                          '$vaccinationScheduleLayers[newcastle_1]', 
                                          '$vaccinationScheduleLayers[bronchitis_1]', 
                                          '$vaccinationScheduleLayers[newcastle_2]', 
                                          '$vaccinationScheduleLayers[bronchitis_2]', 
                                          '$vaccinationScheduleLayers[fowl_pox]'
                                      )";
                        if ($conn->query($insertSql) === TRUE) {
                            echo "comfimation  success";
                        } else {
                            echo "Error: " . $insertSql . "<br>" . $conn->error;
                        }
                    }
                    elseif ($poultryType === "broilers") {
                        // Calculate vaccination dates for broilers
                        // Modify the calculation to start from the retrieved hatching date
                        $hatchDate = strtotime($hatchDate); // Convert to Unix timestamp
                        $vaccinationScheduleBroilers = array(
                            'marek' => date('Y-m-d', $hatchDate), // Day 1: Marek's disease
                            'bronchitis_1' => date('Y-m-d', strtotime('+1 week', $hatchDate)), // Week 1: Infectious bronchitis (1st dose)
                            'newcastle_1' => date('Y-m-d', strtotime('+2 weeks', $hatchDate)), // Week 2: Newcastle disease (1st dose)
                            'bronchitis_2' => date('Y-m-d', strtotime('+3 weeks', $hatchDate)), // Week 3: Infectious bronchitis (2nd dose)
                            'newcastle_2' => date('Y-m-d', strtotime('+4 weeks', $hatchDate)) // Week 4: Newcastle disease (2nd dose)
                        );
                    
                        // Insert calculated vaccination schedule into vaccination_c table
                        $insertSql = "INSERT INTO vaccination_c (Marek, bronchitis1, Newcastle1, bronchitis2, Newcastle2) 
                                      VALUES (
                                          '$vaccinationScheduleBroilers[marek]', 
                                          '$vaccinationScheduleBroilers[bronchitis_1]', 
                                          '$vaccinationScheduleBroilers[newcastle_1]', 
                                          '$vaccinationScheduleBroilers[bronchitis_2]', 
                                          '$vaccinationScheduleBroilers[newcastle_2]'
                                      )";
                        if ($conn->query($insertSql) === TRUE) {
                            echo "press finish button to finalise";
                        } else {
                            echo "Error: " . $insertSql . "<br>" . $conn->error;
                        }
                    }
                    
                
                break;

            // Add other cases if needed

            default:
                // Handle other actions if necessary
                break;
        }
    } else {
        echo "No records found in the remind table.";
    }
}

// Close connection
$conn->close();

?>
