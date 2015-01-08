<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * Insert data from XMLs to database
 */

$import = $container->getService('databaseImport');
$import->import();
