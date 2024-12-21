<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "maindatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

// Prepare and bind
$stmt = $conn->prepare("INSERT INTO storageloss (Year, StorageLossPercentage, StorageLossMillion) VALUES (?, ?, ?)");
$stmt->bind_param("iii", $year, $lossPercentage, $lossMillion);

foreach ($data as $row) {
  $year = (int)$row[0];
  $lossPercentage = (int)$row[1];
  $lossMillion = (int)$row[2];
  $stmt->execute();
}

$stmt->close();
$conn->close();
echo "Data inserted successfully!";
?>
