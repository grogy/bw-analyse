<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * Search movies with missing CSFD information in inbox and set information in hyperlinks
 */

$databaseSelect = $container->getService('databaseSelect');
$csfdInInbox = new CsfdInInbox();
foreach ($databaseSelect->getArticleByPortal('Film') as $movie) {
    if (!$csfdInInbox->isMovie($movie['text'])) {
        continue;
    }
    if ($csfdInInbox->existsCsfdInformationInInbox($movie['text'])) {
        continue;
    }
    if (!$csfdInInbox->existsCsfdInformationInHyperlinks($movie['text'])) {
        continue;
    }
    $csfdID = $csfdInInbox->getCsfdId($movie['text']);
    $movieName = $movie['name'];
    echo "You can insert CSFD ID to movie inbox. CSFD is $csfdID, movie name is '$movieName'.\n";
}
