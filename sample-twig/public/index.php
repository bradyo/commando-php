<?php
require_once(__DIR__ . '/../bootstrap.php');

$config = include(__DIR__ . '/../config/config.php');
$app = new \SampleTwig\Application($config);
$app->handleRequest();
