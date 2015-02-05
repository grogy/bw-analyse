<?php

use Tester\Assert;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/libs/DatabaseImport.php';
Tester\Environment::setup();

$databaseMock = \Mockery::mock('Nette\Database\Connection')->makePartial();
$import = new DatabaseImport($databaseMock);


// check get categories
$text = <<<'EOT'
Text of article.
[[Kategorie:First category]]
EOT;
$expected = ['First category'];
Assert::same($expected, $import->getCategories($text));

$text = <<<'EOT'
Text of article.
[[Kategorie:First category]]
[[Kategorie:Second category]]
[[Kategorie: Third category name ]]
EOT;
$expected = [
    'First category',
    'Second category',
    'Third category name',
];
Assert::same($expected, $import->getCategories($text));

$text = <<<'EOT'
Text of article.
[[Kategorie:First category]]
[[Kategorie:Second category|Third category]]
EOT;
$expected = [
    'First category',
    'Second category',
    'Third category',
];
Assert::same($expected, $import->getCategories($text));

$text = <<<'EOT'
Text of article.
[[Category:First category]]
[[Category:Second category|Third category]]
EOT;
$expected = [
    'First category',
    'Second category',
    'Third category',
];
Assert::same($expected, $import->getCategories($text));


// check get portals
$text = <<<'EOT'
Text of article.
{{Portály|Film}}
EOT;
$expected = ['Film'];
Assert::same($expected, $import->getPortals($text));

$text = <<<'EOT'
Text of article.
{{Portály|Film and cinema}}
{{Portály| Music }}
EOT;
$expected = [
    'Film and cinema',
    'Music',
];
Assert::same($expected, $import->getPortals($text));

$text = <<<'EOT'
Text of article.
{{Portály|Film|Music|Math}}
EOT;
$expected = [
    'Film',
    'Music',
    'Math',
];
Assert::same($expected, $import->getPortals($text));

$text = <<<'EOT'
Text of article.
{{Portály|Film}}
{{Portal|Music|Math}}
EOT;
$expected = [
    'Film',
    'Music',
    'Math',
];
Assert::same($expected, $import->getPortals($text));

$text = <<<'EOT'
<div style="font-size:90%;">
{{Portál:Literatura/Odkazy}}
</div>
|}
|}

<div style="text-align: justify; background: #fee; padding: 0px 10px; border: 1px solid #fbb">
* Z&nbsp;článků týkajících se obsahu portálu můžete na tento portál odkázat šablonou <code><nowiki>{{Portály|Literatura}}</nowiki></code> umístěnou na konci článku těsně nad kategoriemi, resp. <code><nowiki>{{DEFAULTSORT:}}</nowiki></code>. V&nbsp;případě, že již článek odkazuje na jiný portál, přidejte odkaz abecedně do již vložené šablony <code><nowiki>{{Portály}}</nowiki></code>, viz [[Šablona:Portály|návod]].
* Krátké články by měly být označeny šablonou {{Šablona|Pahýl}}
</div>
EOT;
$expected = [
    'Literatura',
];
Assert::same($expected, $import->getPortals($text));
