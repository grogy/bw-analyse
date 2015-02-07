<?php

use Tester\Assert;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/libs/CsfdInInbox.php';
Tester\Environment::setup();

$csfdInInbox = new CsfdInInbox();


$articleText = <<<'EOT'
{{Infobox - film
 | šířka =
 | film = Andula vyhrála
 | obrázek =
 | rozpočet =
 | imdb = 0166479
}}
'''''Andula vyhrála''''' je [[československo|československý]] [[film]] z roku [[1937]].

== Obsah filmu ==
{{Spoiler}}
Content..
{{endspoiler}}
EOT;

$articleText = <<<'EOT'
Content of article
EOT;
Assert::false($csfdInInbox->isMovie($articleText));


$articleText = <<<'EOT'
{{Infobox - film
 | šířka =
 | film = Andula vyhrála
 | obrázek =
 | rozpočet =
 | čsfd =
 | imdb = 0166479
}}
'''''Andula vyhrála''''' je [[československo|československý]] [[film]] z roku [[1937]].

== Obsah filmu ==
{{Spoiler}}
Content..
{{endspoiler}}

* {{Čsfd film|id=1552}}
EOT;
Assert::true($csfdInInbox->isMovie($articleText));
Assert::false($csfdInInbox->existsCsfdInformationInInbox($articleText));
Assert::true($csfdInInbox->existsCsfdInformationInHyperlinks($articleText));
Assert::same('1552', $csfdInInbox->getCsfdId($articleText));


$articleText = <<<'EOT'
Content.. http://www.csfd.cz/film/10089-byl-jednou-jeden-kral/ content..
EOT;
Assert::true($csfdInInbox->existsCsfdInformationInHyperlinks($articleText));
Assert::same('10089', $csfdInInbox->getCsfdId($articleText));
