<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mod_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT mod_key, COUNT(*) as download_count FROM downloads GROUP BY mod_key";
$result = $conn->query($sql);

$counts = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $counts[$row["mod_key"]] = $row["download_count"];
    }
}

header('Content-Type: application/json');
echo json_encode($counts);

$conn->close();
?>
