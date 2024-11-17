<?php
$counterFile = 'downloads_count.json';
$file_paths = array(
    "ako_tamaki_v2.3" => "https://icedrive.net/s/6zwYCYDyRZS8yb95YajXGfXbfx8u",
    "kurisu_makise_v1.3" => "https://icedrive.net/s/GtuPFkWDhxC3Q82kX57T9WZYt6Z7"
);

// Increment counter and redirect
if (isset($_GET['file'])) {
    $fileKey = $_GET['file'];
    $counts = file_exists($counterFile) ? json_decode(file_get_contents($counterFile), true) : [];

    if (isset($counts[$fileKey])) {
        $counts[$fileKey]++;
    } else {
        $counts[$fileKey] = 1;
    }
    file_put_contents($counterFile, json_encode($counts));

    if (array_key_exists($fileKey, $file_paths)) {
        header("Location: " . $file_paths[$fileKey]);
        exit;
    } else {
        echo "Invalid file request.";
    }
}

// Fetch download counts
if (isset($_GET['getCounts'])) {
    if (file_exists($counterFile)) {
        echo json_encode(json_decode(file_get_contents($counterFile), true));
    } else {
        echo json_encode([]);
    }
    exit;
}

echo "Invalid request.";
?>
