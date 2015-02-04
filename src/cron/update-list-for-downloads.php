<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * Update lists of links for downloads
 */

$listOfLinks = $container->getService('listOfLinks');
$listOfLinks->generateDownloadFile(TEMP_DIR . '/wiki/files-for-download.sh');
