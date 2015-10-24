<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * Find missing assigment portals to articles
 */

$missingPortals = $container->getService('missingPortals');
$missingPortals->process();
