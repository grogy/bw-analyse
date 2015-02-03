<?php

define('TEMP_DIR', __DIR__ . '/../temp');
include __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(TEMP_DIR);
$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->createRobotLoader()->addDirectory(__DIR__)->register();
$container = $configurator->createContainer();
return $container;
