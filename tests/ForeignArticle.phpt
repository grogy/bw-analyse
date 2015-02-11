<?php

use Tester\Assert;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/libs/ForeignArticle.php';
Tester\Environment::setup();

$databaseMock = \Mockery::mock('Nette\Database\Connection')->makePartial();
$foreignArticle = new ForeignArticle($databaseMock);




$articleHTML = <<<'EOT'
<html>
<head></head>
<body>
    <ul>
        <li class="interlanguage-link interwiki-cy"><a href="//cy.wikipedia.org/wiki/PHP" title="PHP – velština" lang="cy" hreflang="cy">Cymraeg</a></li>
        <li class="interlanguage-link interwiki-da"><a href="//da.wikipedia.org/wiki/PHP" title="PHP – dánština" lang="da" hreflang="da">Dansk</a></li>
        <li class="interlanguage-link interwiki-de"><a href="//de.wikipedia.org/wiki/PHP" title="PHP – němčina" lang="de" hreflang="de">Deutsch</a></li>
        <li class="interlanguage-link interwiki-el"><a href="//el.wikipedia.org/wiki/PHP" title="PHP – řečtina" lang="el" hreflang="el">Ελληνικά</a></li>
        <li class="interlanguage-link interwiki-en badge-Q17437798 badge-goodarticle" title="dobrý článek">
            <a href="//en.wikipedia.org/wiki/PHP" title="PHP – angličtina" lang="en" hreflang="en">English</a>
        </li>
    </ul>
</body>
</html>
EOT;
$expected = '//en.wikipedia.org/wiki/PHP';
Assert::same($expected, $foreignArticle->getLink('en', $articleHTML));
