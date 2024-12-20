<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = ""; // Default XAMPP password is empty
$database = "dbms2";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get JSON data from POST request
$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data)) {
    foreach ($data as $row) {
        $year = intval($row['year']);
        $lossTons = floatval($row['lossTons']);
        $lossDollars = floatval($row['lossDollars']);
        $profit = floatval($row['profit']);

        $stmt = $conn->prepare("INSERT INTO transport_data (year, transport_loss_tons, transport_loss_dollars, profit) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iddd", $year, $lossTons, $lossDollars, $profit);

        if (!$stmt->execute()) {
            echo json_encode(["status" => "error", "message" => "Error inserting data: " . $stmt->error]);
            exit();
        }
    }
    echo json_encode(["status" => "success", "message" => "Data inserted successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "No data received"]);
}

$conn->close();
?>
