<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/cswiki/library.php';

Tester\Environment::setup();


$text = <<<'EOT'
Text of article.
[[Kategorie:First category]]
EOT;
$expected = ['First category'];
Assert::same($expected, getCategories($text));


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
Assert::same($expected, getCategories($text));


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
Assert::same($expected, getCategories($text));


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
Assert::same($expected, getCategories($text));
