<?php
// Map the file keys to their actual download URLs (external Icedrive links)
$file_paths = array(
    "ako_tamaki_v2.3" => "https://icedrive.net/s/6zwYCYDyRZS8yb95YajXGfXbfx8u",
    "kurisu_makise_v1.3" => "https://icedrive.net/s/GtuPFkWDhxC3Q82kX57T9WZYt6Z7"
);

// Check if the 'file' parameter is present in the URL
if (isset($_GET['file'])) {
    $fileKey = $_GET['file'];

    // Increment the download counter
    $counterFile = 'downloads_count.json';
    $counts = file_exists($counterFile) ? json_decode(file_get_contents($counterFile), true) : [];

    // Increment the count for the specified file
    if (isset($counts[$fileKey])) {
        $counts[$fileKey]++;
    } else {
        $counts[$fileKey] = 1;
    }

    // Save the updated counts back to the JSON file
    file_put_contents($counterFile, json_encode($counts));

    // Check if the file key exists in the array and redirect to the external URL
    if (array_key_exists($fileKey, $file_paths)) {
        $fileUrl = $file_paths[$fileKey];
        
        // Redirect the user to the external download URL
        header("Location: $fileUrl");
        exit;
    } else {
        echo "Invalid file request.";
    }
} else {
    echo "No file specified.";
}
?>
