<?php

if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die('Neexistuje obsah slozky vendor! Zpracujte prosim composer!');
}

require __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;

$environment = 'production';
$isDebug = false;

if (file_exists(__DIR__ . '/.development')) {
    $environment = 'development';
    $isDebug = true;
}

if (file_exists(__DIR__ . '/.stage')) {
    $environment = 'stage';
    $isDebug = false;
}

$configurator->setDebugMode($isDebug); // zapinani ladenky

$configurator->addParameters([
    'environment' => $environment,
    'rootDir' => dirname(__DIR__),
    'vendorDir' => dirname(__DIR__) . '/vendor',
    'vendorQ2Dir' => dirname(__DIR__) . '/vendor/q2'
]);

if(php_sapi_name() != 'cli') {
    $configurator->enableDebugger(__DIR__ . '/../log');
    $configurator->setTempDirectory(__DIR__ . '/../temp');
}else {
    $configurator->enableDebugger(__DIR__ . '/../log-cli');
    $configurator->setTempDirectory(__DIR__ . '/../temp-cli');
}

$configurator->createRobotLoader()
    ->addDirectory(__DIR__)
    ->register();

Tracy\Debugger::$maxDepth = 5;

$configurator->addConfig(__DIR__ . '/config/core/config.neon');
$configurator->addConfig(__DIR__ . '/config/core/extensions.neon');
$configurator->addConfig(__DIR__ . '/config/core/parameters.neon');
$configurator->addConfig(__DIR__ . '/config/core/services.neon');
$configurator->addConfig(__DIR__ . '/config/core/console.neon');

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/extensions.neon');
$configurator->addConfig(__DIR__ . '/config/parameters.neon');
$configurator->addConfig(__DIR__ . '/config/services.neon');
$configurator->addConfig(__DIR__ . '/AdminModule/config/admin.neon');
$configurator->addConfig(__DIR__ . '/config/console.neon');

if (file_exists(__DIR__ . '/.development')) {
    $configurator->addConfig(__DIR__ . '/config/config-development.neon');
}

if (file_exists(__DIR__ . '/.stage')) {
    $configurator->addConfig(__DIR__ . '/config/config-stage.neon');
}

$container = $configurator->createContainer();

return $container;
