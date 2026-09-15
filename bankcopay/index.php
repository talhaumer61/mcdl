<?php

require_once __DIR__ . '/bootstrap.php';

$logDir = __DIR__ . '/logs';
// Log file
$logFile = $logDir . '/ips_' . date('Y-m-d') . '.txt';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
$dblms      = new dblms();

if(getUriSegment(1) == 'success') {
    include_once("success.php");
    exit();
} else {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Direct browser access not allowed']);
    exit;
}
