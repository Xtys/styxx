<?php
$file_paths = array(
    "ako_tamaki_v2.3" => "https://icedrive.net/s/6zwYCYDyRZS8yb95YajXGfXbfx8u",
    "kurisu_makise_v1.3" => "https://icedrive.net/s/GtuPFkWDhxC3Q82kX57T9WZYt6Z7"
);

if (isset($_GET['file'])) {
    $file_key = $_GET['file'];
    
    if (array_key_exists($file_key, $file_paths)) {
        header("Location: " . $file_paths[$file_key]);
        exit;
    } else {
        echo "Invalid file request.";
        exit;
    }
}

http_response_code(400);
echo "No file specified.";
?>
