<?php

use Tester\Assert;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/libs/InfoBoxes/InfoBox.php';
require __DIR__ . '/../src/libs/InfoBoxes/InfoBoxParser.php';
Tester\Environment::setup();




$articleSource = <<<'EOT'
{{Infobox - film
 | šířka =
 | film = Harry Potter a Kámen mudrců
 | obrázek =
 | popisek =
 | originál = Harry Potter and the Philosopher's Stone
 | žánr = [[fantasy]]
 | režie = [[Chris Columbus]]
 | produkce = [[David Heyman]]
 | předchozí =
 | imdb = 0241527
}}

'''Harry Potter a Kámen mudrců''' je [[Spojené království|britsko]]-americký film z roku [[2001]]...

== Externí odkazy ==
* {{Čsfd film|1626}}

{{Portály|Harry Potter}}

[[Kategorie:Britské fantastické filmy]]
[[Kategorie:Filmy o Harrym Potterovi]]
EOT;
$actualInfoBox = parseInfoBoxFromArticle($articleSource);
Assert::equal('film', $actualInfoBox->getName());
Assert::equal('Harry Potter a Kámen mudrců', $actualInfoBox->getProperty('film'));
Assert::equal('Harry Potter and the Philosopher\'s Stone', $actualInfoBox->getProperty('originál'));
Assert::equal('0241527', $actualInfoBox->getProperty('imdb'));
