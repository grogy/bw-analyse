<?php

use Tester\Assert;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/libs/InfoBoxes/InfoBox.php';
require __DIR__ . '/../src/libs/InfoBoxes/DifferentBetweenInfoBoxes.php';
require __DIR__ . '/../src/libs/InfoBoxes/ComparatorForInfoBoxes.php';
Tester\Environment::setup();

$firstParameters = [
    'fillm' => 'Harry Potter a Kámen mudrců',
    'originál' => 'Harry Potter and the Philosopher\'s Stone',
    'imdb' => '0241527',
    'režisér' => '[[Chris Columbus]]',
];
$firstInfoBox = new InfoBox('film', $firstParameters);

$secondParameters = [
    'name' => 'Harry Potter and the Philosopher\'s Stone',
    'year' => '2001'
];
$secondInfoBox = new InfoBox('film', $secondParameters);


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
        'cs' => 'rok',
        'en' => 'year',
        'type' => 'is-optional-cs',
        'notice' => 'Článek v angličtině obsahuje rok',
    ],
];
$diff = getListOfDifferences($firstInfoBox, $secondInfoBox, $rules);
$expectedDiff = [
    'Film musí mít vyplněn název',
    'Článek v angličtině obsahuje rok',
];
Assert::same($expectedDiff, $diff);
