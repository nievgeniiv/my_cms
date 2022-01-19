<?php

include_once DIR_MAIN . 'Psr4AutoloaderClass.php';
$loader = new Psr4AutoloaderClass();
$listNameSpace = [
	'src\main\models' => DIR_MAIN . '/models',
	'src\main\modules\cms' => DIR_MAIN . '/modules/cms',
	'src\main\modules\common' => DIR_MAIN . '/modules/common',
	'src\main\modules\public' => DIR_MAIN . '/modules/public',
	'src\main\services' => DIR_MAIN . '/services',
];
foreach ($listNameSpace as $nameSpace => $dir) {
	$loader->addNamespace($nameSpace, $dir);
}

$loader->register();

$ln = isset($_GET['ln']) ? $_GET['ln'] : 'ru';
require_once DIR_VALUES . 'strings.' . $ln . '.php';
