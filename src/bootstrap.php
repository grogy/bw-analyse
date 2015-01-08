<?php

include __DIR__ . '/../vendor/autoload.php';

$configurator = new Nette\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../temp');
$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->createRobotLoader()->addDirectory(__DIR__)->register();
$container = $configurator->createContainer();
return $container;
