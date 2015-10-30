<?php

include_once __DIR__ . '/../bootstrap.php';
include_once __DIR__ . '/../../src/libs/InfoBoxes/InfoBoxParser.php';
include_once __DIR__ . '/../../src/libs/InfoBoxes/DifferentBetweenInfoBoxes.php';
include_once __DIR__ . '/../../src/libs/InfoBoxes/ComparatorForInfoBoxes.php';

/**
 * Search problems in incomplete information in info boxes
 * cs: https://cs.wikipedia.org/wiki/%C5%A0ablona:Infobox_-_film
 * en: https://en.wikipedia.org/wiki/Template:Infobox_film
 */

$englishPages = $container->getService('englishPages');
$proposalImprove = $container->getService('proposalImprove');

$rules = [
    [
        'cs' => 'film',
        'type' => 'have-to-be-here',
        'notice' => 'Film musí mít vyplněn název',
    ],
    [
        'cs' => 'originál',
        'en' => 'name',
        'type' => 'have-to-be-same',
        'notice' => 'Originální názvy filmu se neshodují',
    ],
    [
        'cs' => 'režie',
        'en' => 'director',
        'type' => 'is-optional-cs',
        'notice' => 'Článek v angličtině obsahuje režiséra',
    ],
    [
        'cs' => 'režie',
        'en' => 'directors',
        'type' => 'is-optional-cs',
        'notice' => 'Článek v angličtině obsahuje režiséry',
    ],
    [
        'cs' => 'zvuk',
        'en' => 'music',
        'type' => 'is-optional-cs',
        'notice' => 'Článek v angličtině obsahuje informaci o zvuku',
    ],
];

$englishPages->cleanAllProposal();
$i = 0;
while (true) {
    $pages = $englishPages->getPairsPages($i++);
    if (empty($pages)) {
        break;
    }
    foreach ($pages as $pair) {
        if (!hasInfoBox($pair['czech'])) {
            continue;
        }
        $czechInfoBox = parseInfoBoxFromArticle($pair['czech']);
        $englishInfoBox = parseInfoBoxFromArticle($pair['english']);
        $differences = getListOfDifferences($czechInfoBox, $englishInfoBox, $rules);
        if (count($differences) > 0) {
            $notice = "Byl nalazen problém v infoboxu článku.\n\n" . join('. ', $differences);
            $proposalImprove->insertToDatabase($pair['id'], 2, $notice);
        }
    }
}
