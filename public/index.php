<?php

use src\main\modules\common\Router;

session_start();
require_once __DIR__ . '/../src/main_config.php';
require_once DIR_MAIN . 'constants.php';
require_once DIR_MAIN . 'functions/common.php';
require_once DIR_MAIN . 'Main.php';
require_once DIR_MAIN . 'Routers.php';

$router = new Router();
$router->go();
