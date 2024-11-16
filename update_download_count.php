<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mod_database";

// Get the file name from the query parameter
$file_key = $_GET['file'];

// Set up your database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Update download count in the database
$sql = "UPDATE downloads SET download_count = download_count + 1 WHERE mod_key = '$file_key'";
$conn->query($sql);

// Retrieve updated count
$sql = "SELECT download_count FROM downloads WHERE mod_key = '$file_key'";
$result = $conn->query($sql);
$row = $result->fetch_assoc();

header('Content-Type: application/json');
echo json_encode(array("downloadCount" => $row["download_count"]));

$conn->close();
?>
