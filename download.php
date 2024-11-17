<?php
// Map the file key to actual file URLs
$file_paths = array(
    "ako_tamaki_v2.3" => "https://icedrive.net/s/6zwYCYDyRZS8yb95YajXGfXbfx8u",
    "kurisu_makise_v1.3" => "https://icedrive.net/s/GtuPFkWDhxC3Q82kX57T9WZYt6Z7"
);

if (isset($_GET['file'])) {
    $fileKey = $_GET['file'];
    
    // Check if the file exists in the array
    if (array_key_exists($fileKey, $file_paths)) {
        // Redirect the user to the external Icedrive URL
        header("Location: " . $file_paths[$fileKey]);
        exit;
    } else {
        echo "Invalid file request.";
        exit;
    }
}

http_response_code(400);
echo "No file specified.";
?>
