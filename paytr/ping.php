<?php
// Simple Health Check
$logDir = __DIR__ . '/../storage/logs';
if (!is_dir($logDir)) mkdir($logDir, 0775, true);

$file = $logDir . '/ping.log';
$content = date('Y-m-d H:i:s') . " - PING OK" . PHP_EOL;

if(file_put_contents($file, $content, FILE_APPEND)) {
    echo "OK";
} else {
    echo "FAIL: Cannot write to log";
}
