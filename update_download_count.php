<?php
$servername = "localhost"; // Database server
$username = "root"; // Database username
$password = ""; // Database password
$dbname = "mod_database"; // Database name

// Get the file name from the query parameter
$file_key = $_GET['file'];

// Set up your database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to get user's IP and determine country (using an external API)
function getUserCountry($ip) {
    $url = "http://ip-api.com/json/{$ip}";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['country'] ?? 'Unknown';
}

$user_ip = $_SERVER['REMOTE_ADDR'];
$country = getUserCountry($user_ip);

// Update download count in the database
$sql = "INSERT INTO downloads (mod_key, country) VALUES ('$file_key', '$country')";
if ($conn->query($sql) === TRUE) {
    // Log the successful update
    error_log("Download count updated successfully for file: $file_key from country: $country");
} else {
    error_log("Error updating download count: " . $conn->error);
}

$conn->close();
?>
