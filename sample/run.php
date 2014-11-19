<?php
require_once(__DIR__ . '/bootstrap.php');
$app = new \Sample\Application(__DIR__ . '/config/config.php');
$app->handleShell();
