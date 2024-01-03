<?php
$fileId = $_GET['id'];

$text = file_get_contents('redirect.txt');

$lines = explode("\n", $text);
$line = array_filter($lines, function($line) use ($fileId) {
    return strpos($line, $fileId . '=') === 0;
});

if (empty($line)) {
    die('File not found');
}

$cdnUrl = explode('=', reset($line))[1];

header('Location: ' . $cdnUrl);
exit;
?>
