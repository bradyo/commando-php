<?php
require_once(__DIR__ . '/../../vendor/autoload.php');

$config = require(__DIR__ . '/../config/config.php');
$app = new \Sample\Application($config);
$app->run();
