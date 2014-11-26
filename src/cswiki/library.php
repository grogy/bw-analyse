<?php

/**
 * Get categories from Wikipedia page text
 * @param string $text
 * @return array
 */
function getCategories($text)
{
    preg_match_all('/\[\[Kategorie:(.+)\]\]/', $text, $matches);
    foreach ($matches[1] as &$match) {
        $match = trim($match);
    }
    return $matches[1];
}



/**
 * Get portals from Wikipedia page text
 * @param string $text
 * @return array
 */
function getPortals($text)
{
    preg_match_all('/{{Portály\|(.+)}}/', $text, $matches);
    foreach ($matches[1] as &$match) {
        $match = trim($match);
    }
    return $matches[1];
}



/**
 * Save article
 * @todo missing test
 * @param \Nette\Database\Connection $database connect to database
 * @param string $title title of article
 * @param array $categories categories of article
 * @param array $portals portals of article
 */
function saveArticle(\Nette\Database\Connection $database, $title, array $categories, array $portals)
{
	$query = 'INSERT INTO articles (name) VALUES (?)';
	$database->query($query, $title);
	$articleId = $database->getInsertId();
	foreach ($categories as $category) {
		$query = 'INSERT INTO categories (name) VALUES (?)';
		$database->query($query, $category);
		$categoryId = $database->getInsertId();
		$query = 'INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)';
		$database->query($query, $articleId, $categoryId);
	}
	foreach ($portals as $portal) {
		$query = 'INSERT INTO portals (name) VALUES (?)';
		$database->query($query, $portal);
		$portalId = $database->getInsertId();
		$query = 'INSERT INTO article_portals (article_id, portal_id) VALUES (?, ?)';
		$database->query($query, $articleId, $portalId);
	}
}



/**
 * Import data with articles to database
 * @todo missing test
 * @param \Nette\Database\Connection $database connect to database
 * @param string $pathToXml path to XML with articles
 */
function importToDatabase(\Nette\Database\Connection $database, $pathToXml)
{
	$doc = new DOMDocument;
	$reader = new XMLReader();
	$reader->open($pathToXml);
	while ($reader->read() && $reader->name !== 'page'); // jump to page elements
	while ($reader->name === 'page') {
		$page = simplexml_import_dom($doc->importNode($reader->expand(), true));
		$reader->next('page');
		$title = (string) $page->title;
		$categories = getCategories($page->revision->text);
		$portals = getPortals($page->revision->text);
		saveArticle($database, $title, $categories, $portals);
	}
}
