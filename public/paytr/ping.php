<?php
// public/paytr/ping.php

// Define Root Path
$root = dirname(__DIR__, 2);

// Logging
$logDir = $root . '/storage/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0775, true);
}
$logFile = $logDir . '/ping.log';

// content
$content = date('Y-m-d H:i:s') . " - PING CHECK FROM " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . PHP_EOL;

if (file_put_contents($logFile, $content, FILE_APPEND)) {
    echo "OK";
} else {
    // Attempt PHP error log fallback
    error_log("PayTR Ping Failed: Cannot write to $logFile");
    echo "FAIL: Cannot write to log";
}
