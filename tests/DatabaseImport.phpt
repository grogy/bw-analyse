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
