<?php
// Get the file name from the query parameter
$file_key = isset($_GET['file']) ? $_GET['file'] : null;

// Map the file key to actual file paths (stored externally)
$file_paths = array(
    "ako_tamaki_v2.3" => "https://icedrive.net/s/6zwYCYDyRZS8yb95YajXGfXbfx8u",
    "kurisu_makise_v1.3" => "https://icedrive.net/s/GtuPFkWDhxC3Q82kX57T9WZYt6Z7"
);

// Define the path to the download counter JSON file
$counterFile = 'downloads_count.json';

// Ensure the counter file exists
if (!file_exists($counterFile)) {
    file_put_contents($counterFile, '{}');
    chmod($counterFile, 0777); // Make the file writable
}

// Function to update the download counter
function updateCounter($fileKey) {
    global $counterFile;
    $counts = json_decode(file_get_contents($counterFile), true) ?? [];

    // Increment the count
    if (isset($counts[$fileKey])) {
        $counts[$fileKey]++;
    } else {
        $counts[$fileKey] = 1;
    }

    // Save the updated counts back to the JSON file
    file_put_contents($counterFile, json_encode($counts));
}

// Step 1: Check if the file key is valid
if ($file_key && array_key_exists($file_key, $file_paths)) {
    // Step 2: Update the download counter
    updateCounter($file_key);

    // Step 3: Redirect to the Icedrive URL
    header("HTTP/1.1 302 Found");
    header("Location: " . $file_paths[$file_key]);
    exit;
} else {
    // Handle invalid file requests
    http_response_code(404);
    echo "Invalid file request.";
    exit;
}
?>
