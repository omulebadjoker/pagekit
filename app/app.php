<?php

use Pagekit\Application as App;
use Pagekit\Module\Loader\ConfigLoader;

$loader = require __DIR__.'/autoload.php';
$config = require __DIR__.'/config.php';

$app = new App($config['values']);
$app['autoloader'] = $loader;

date_default_timezone_set('UTC');

$app['module']->addPath([
    __DIR__.'/modules/*/module.php',
    __DIR__.'/installer/module.php',
    $app['path.extensions'].'/*/extension.php',
    $app['path.themes'].'/*/theme.php'
]);

$app['module']->addLoader(new ConfigLoader($config));

if (!$app['config.file']) {

    $requirements = require __DIR__.'/installer/requirements.php';

    if ($failed = $requirements->getFailedRequirements()) {
        require __DIR__.'/installer/views/requirements.php';
        exit;
    }

    $config->load(__DIR__.'/installer/config.php');

    $app['module']->load('installer');

} else {

    $app['module']->load('system');

}

return $app;
