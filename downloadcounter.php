<?php
$file_paths = array(
    "ako_tamaki_v2.3" => "https://icedrive.net/s/6zwYCYDyRZS8yb95YajXGfXbfx8u",
    "kurisu_makise_v1.3" => "https://icedrive.net/s/GtuPFkWDhxC3Q82kX57T9WZYt6Z7"
);

$counterFile = 'downloads_count.json';

if (!file_exists($counterFile)) {
    file_put_contents($counterFile, '{}');
    chmod($counterFile, 0777);
}

function updateCounter($fileKey) {
    global $counterFile;

    $counts = json_decode(file_get_contents($counterFile), true);
    if (isset($counts[$fileKey])) {
        $counts[$fileKey]++;
    } else {
        $counts[$fileKey] = 1;
    }

    file_put_contents($counterFile, json_encode($counts));
}

if (isset($_GET['getCounts'])) {
    echo file_get_contents($counterFile);
    exit;
}

if (isset($_GET['file'])) {
    $fileKey = $_GET['file'];
    updateCounter($fileKey);
    echo "Counter updated.";
    exit;
}

http_response_code(400);
echo "Invalid request.";
?>
