<?php

use Tester\Assert;
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/libs/ListOfLinks.php';
Tester\Environment::setup();

$databaseMock = \Mockery::mock('Nette\Database\Connection')->makePartial();
$listOfLinks = new ListOfLinks($databaseMock,  __DIR__ . '/input/');

// check all languages types
Assert::same('cswiki/20150105', $listOfLinks->getLanguagePages()['cswiki']['url']);
Assert::type('DateTime', $listOfLinks->getLanguagePages()['cswiki']['time']);
Assert::true($listOfLinks->getLanguagePages()['cswiki']['done']);
Assert::false($listOfLinks->getLanguagePages()['dewiki']['done']);

// check files of concrete languages versions
$currentPage = $listOfLinks->getFiles('cswiki')['All pages, current versions only.'];
Assert::same('/cswiki/20150105/cswiki-20150105-pages-meta-current.xml.bz2', $currentPage[0]->url);
$currentPage = $listOfLinks->getFiles('enwiki')['All pages, current versions only.'];
Assert::same('/enwiki/20141208/enwiki-20141208-pages-meta-current1.xml-p000000010p000010000.bz2', $currentPage[0]->url);
