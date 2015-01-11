<?php

use Tester\Assert;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/libs/ListOfLinks.php';
Tester\Environment::setup();

$databaseMock = \Mockery::mock('Nette\Database\Connection')->makePartial();
$listOfLinks = new ListOfLinks($databaseMock,  __DIR__ . '/input/');


Assert::same('cswiki/20150105', $listOfLinks->getLanguagePages()['cswiki']['url']);
Assert::type('DateTime', $listOfLinks->getLanguagePages()['cswiki']['time']);
Assert::true($listOfLinks->getLanguagePages()['cswiki']['done']);
Assert::false($listOfLinks->getLanguagePages()['dewiki']['done']);
