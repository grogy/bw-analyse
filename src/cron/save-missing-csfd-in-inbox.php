<?php

include_once __DIR__ . '/../bootstrap.php';

/**
 * Search movies with missing CSFD information in inbox and set information in hyperlinks
 */

$databaseSelect = $container->getService('databaseSelect');
$proposalImprove = $container->getService('proposalImprove');
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
    $notice = "At article '$movieName' in infobox missing ČSFD identifier of movie. ČSFD identifier is '$csfdID'.\n\n";
    $notice .= "Please, insert to infobox ' | čsfd = $csfdID'.";
    $proposalImprove->insertToDatabase($movie['id'], 1, $notice);
}
