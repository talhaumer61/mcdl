<?php
declare(strict_types=1);
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

//if (preg_match('/Chrome|Firefox|Safari|Edge/i', $userAgent)) {
//    http_response_code(403);
//    exit('Browser access is not allowed.');
//}
//
if (isset($_SERVER['HTTP_ORIGIN'])) {
    header('HTTP/1.1 403 Forbidden');
    echo json_encode(['error' => 'Direct browser access not allowed']);
    exit;
}
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Only POST requests are allowed');
}

require_once '../include/dbsetting/lms_vars_config.php';
require_once '../include/dbsetting/classdbconection.php';
require_once '../include/functions/functions.php';

function getUriSegment($index, $default = '') {
    $uri      = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri      = trim(rtrim($uri, '/'), '/');
    $segments = explode('/', $uri);
    return $segments[$index] ?? $default;
}