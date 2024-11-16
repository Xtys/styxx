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
$sql = "INSERT INTO downloads (mod_key) VALUES ('$file_key')";
if ($conn->query($sql) === TRUE) {
    $sql = "SELECT COUNT(*) as download_count FROM downloads WHERE mod_key = '$file_key'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    echo json_encode(array("downloadCount" => $row["download_count"]));
} else {
    echo json_encode(array("error" => "Error updating download count"));
}

$conn->close();
?>
