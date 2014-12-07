<?php

use Tester\Assert;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/cswiki/library.php';

Tester\Environment::setup();


$text = <<<'EOT'
Text of article.
{{Portály|Film}}
EOT;
$expected = ['Film'];
Assert::same($expected, getPortals($text));


$text = <<<'EOT'
Text of article.
{{Portály|Film and cinema}}
{{Portály| Music }}
EOT;
$expected = [
    'Film and cinema',
    'Music',
];
Assert::same($expected, getPortals($text));


$text = <<<'EOT'
Text of article.
{{Portály|Film|Music|Math}}
EOT;
$expected = [
    'Film',
    'Music',
    'Math',
];
Assert::same($expected, getPortals($text));


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
Assert::same($expected, getPortals($text));
