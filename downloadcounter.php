<?php
// Specify the directory where your mod files are located
$modDirectory = 'mods/';

// Check if the 'file' parameter is present in the URL
if (isset($_GET['file'])) {
    $fileKey = $_GET['file'];
    $filePath = $modDirectory . $fileKey . '.zip';

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

    // Check if the file exists before serving it
    if (file_exists($filePath)) {
        // Set headers to download the file
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    } else {
        http_response_code(404);
        echo 'File not found.';
    }
} else {
    http_response_code(400);
    echo 'Invalid request.';
}
?>
