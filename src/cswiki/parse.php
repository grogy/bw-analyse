<?php

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/library.php';

$pathToXml = "/vagrant/download-data/cswiki-latest-pages-articles.xml";
$database = new Nette\Database\Connection('mysql:host=127.0.0.1;dbname=wikipedia', 'root', 'pass');

importToDatabase($database, $pathToXml);
