<?php

//load config

use App\Framework\App;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'autoload.php';
require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'config.php';

session_start();
ini_set('display_errors', 1);
$action = isset($_GET['action']) ? $_GET['action'] : null;

$app = new App();
$response = $app->run($action);
$response->send();