<?php
// Step 1: Define the file paths to the Icedrive links
$file_paths = array(
    "ako_tamaki_v2.3" => "https://icedrive.net/s/6zwYCYDyRZS8yb95YajXGfXbfx8u",
    "kurisu_makise_v1.3" => "https://icedrive.net/s/GtuPFkWDhxC3Q82kX57T9WZYt6Z7"
);

// Step 2: Define the counter file
$counterFile = 'downloads_count.json';

// Create the counter file if it doesn't exist
if (!file_exists($counterFile)) {
    file_put_contents($counterFile, '{}');
}

// Function to update the download counter
function updateCounter($fileKey) {
    global $counterFile;
    $counts = json_decode(file_get_contents($counterFile), true) ?: [];

    // Increment the count
    if (isset($counts[$fileKey])) {
        $counts[$fileKey]++;
    } else {
        $counts[$fileKey] = 1;
    }

    // Save the updated counts
    file_put_contents($counterFile, json_encode($counts));
}

// Step 3: Handle the download request
if (isset($_GET['file'])) {
    $file_key = $_GET['file'];

    // Check if the file key exists
    if (array_key_exists($file_key, $file_paths)) {
        // Step 4: Update the download counter
        updateCounter($file_key);

        // Step 5: Redirect to the actual Icedrive URL
        header("HTTP/1.1 302 Found");
        header("Location: " . $file_paths[$file_key]);
        exit;
    } else {
        echo "Invalid file request.";
        exit;
    }
}

// If no file parameter is given, return an error
http_response_code(400);
echo "No file specified.";
?>
