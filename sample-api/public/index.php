<?php
require_once(__DIR__ . '/../bootstrap.php');

$config = require(__DIR__ . '/../config/config.php');
$app = new \SampleApi\Application($config);
$app->handleRequest();
