<?php
    include("./utils/dbconnection.php");



// Check if data is sent via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stage = $_POST['stage'];
    $lossType = $_POST['lossType'];
    $amount = $_POST['amount'];

    // Prepare SQL query
    $sql = "INSERT INTO harvestdata (stage, loss_type, amount) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssd", $stage, $lossType, $amount); // 'ssd' = string, string, double

    // Execute query
    if ($stmt->execute()) {
        echo "Data inserted successfully!";
    
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
   
}


?>
