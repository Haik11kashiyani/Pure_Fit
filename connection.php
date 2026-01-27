<?php

$localConfig = [
    'host' => 'localhost',
    'database' => 'pure_fit',
    'username' => 'root',
    'password' => '',
];

$hostingConfig = [
    'host' => 'sql101.infinityfree.com',
    'database' => 'if0_40792396_purefit',
    'username' => 'if0_40792396',
    'password' => 'Purefit2025',
];

$httpHost = $_SERVER['HTTP_HOST'] ?? '';
$isCli = PHP_SAPI === 'cli';
$isLocal = $isCli || stripos($httpHost, 'localhost') !== false || $httpHost === '127.0.0.1';

$activeConfig = $isLocal ? $localConfig : $hostingConfig;

$conn = new mysqli(
    $activeConfig['host'],
    $activeConfig['username'],
    $activeConfig['password'],
    $activeConfig['database']
);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

?>