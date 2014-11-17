<?php

require_once __DIR__ . '/library.php';

$xmlfile = "/vagrant/download-data/cswiki-latest-pages-articles.xml";

$doc = new DOMDocument;
$reader = new XMLReader();
$reader->open($xmlfile);

// jump to page elements
while ($reader->read() && $reader->name !== 'page');

while ($reader->name === 'page') {
    $page = simplexml_import_dom($doc->importNode($reader->expand(), true));
    $reader->next('page');

    echo 'Title: ' . $page->title . "\n";
    echo 'Kategory: ' . join(', ', getCategories($page->revision->text)) . "\n";
    echo 'Portal: ' . join(', ', getPortals($page->revision->text)) . "\n\n";
}
