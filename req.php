<?php
include 'config.php';
$org = str_replace(['http://www.', 'https://www.', 'http://', 'https://'], '', $_SERVER['HTTP_ORIGIN']);
$checkOrigin = strpos(join(' ', $ALLOWED_HTTP_ORIGINS), $org) !== false;
if (isset($_SERVER['HTTP_ORIGIN'])) {
    // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one you want to allow
    if ($checkOrigin)
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    else
        exit(0);

    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Max-Age: 86400');    // cache for 1 day
}
header('Access-Control-Allow-Methods: POST, OPTIONS');

// Access-Control headers are received during OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

    exit(0);
}

include 'tracker.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if ($checkOrigin && $data['sn']) {
        record_visit($data['sn']);
    }

    echo json_encode(['response' => 'ok']);
}
