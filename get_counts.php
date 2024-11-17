<?php
$counterFile = 'downloads_count.json';

if (file_exists($counterFile)) {
    $counts = json_decode(file_get_contents($counterFile), true);
    echo json_encode($counts);
} else {
    echo json_encode([]);
}
?>
