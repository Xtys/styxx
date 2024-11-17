<?php
$counterFile = 'downloads_count.json';
if (file_exists($counterFile)) {
    echo file_get_contents($counterFile);
} else {
    echo json_encode([]);
}
?>
