<?php
require_once(__DIR__ . '/../bootstrap.php');
$config = include(__DIR__ . '/../config/config.php');
$app = new \TwigSample\Application($config);
$app->handleRequest();
