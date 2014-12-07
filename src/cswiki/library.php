<?php

/**
 * Get categories from Wikipedia page text
 * @param string $text
 * @return array
 */
function getCategories($text)
{
    $categories = [];
    preg_match_all('/\[\[(Kategorie|Category):(.+)\]\]/', $text, $matches);
    foreach ($matches[2] as &$match) {
        $matchs = explode('|', $match);
        foreach ($matchs as $match) {
            $categories[] = trim($match);
        }
    }
    return $categories;
}



/**
 * Get portals from Wikipedia page text
 * @param string $text
 * @return array
 */
function getPortals($text)
{
    $portals = [];
    preg_match_all('/{{(Portály|Portal)\|(.+)}}/', $text, $matches);
    foreach ($matches[2] as &$match) {
        $matchs = explode('|', $match);
        foreach ($matchs as $match) {
            $portals[] = trim($match);
        }
    }
    return $portals;
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
		$categoryId = getCategoryId($database, $category);
		$query = 'INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)';
		$database->query($query, $articleId, $categoryId);
	}
	foreach ($portals as $portal) {
		$portalId = getPortalId($database, $portal);
		$query = 'INSERT INTO article_portals (article_id, portal_id) VALUES (?, ?)';
		$database->query($query, $articleId, $portalId);
	}
}



/**
 * Get category ID
 * If isn't category save in database then save and return ID.
 * @todo missing test
 * @param \Nette\Database\Connection $database connect to database
 * @param string $categoryName name of category
 */
function getCategoryId($database, $categoryName)
{
	$query = 'SELECT id FROM categories WHERE name = ?';
	$categoryId = $database->query($query, $categoryName)->fetchField('id');
	if (empty($categoryId)) {
		$query = 'INSERT INTO categories (name) VALUES (?)';
		$database->query($query, $categoryName);
		$categoryId = $database->getInsertId();
	}
	return $categoryId;
}



/**
 * Get portal ID
 * If isn't portal save in database then save and return ID.
 * @todo missing test
 * @param \Nette\Database\Connection $database connect to database
 * @param string $portalName name of portal
 */
function getPortalId($database, $portalName)
{
	$query = 'SELECT id FROM portals WHERE name = ?';
	$portalId = $database->query($query, $portalName)->fetchField('id');
	if (empty($portalId)) {
		$query = 'INSERT INTO portals (name) VALUES (?)';
		$database->query($query, $portalName);
		$portalId = $database->getInsertId();
	}
	return $portalId;
}



/**
 * Clean records from database
 * @todo missing test
 * @param \Nette\Database\Connection $database connect to database
 */
function cleanDatabase(\Nette\Database\Connection $database)
{
	$query = 'SET FOREIGN_KEY_CHECKS = 0';
	$database->query($query);
	$query = 'TRUNCATE TABLE article_portals';
	$database->query($query);
	$query = 'TRUNCATE TABLE article_categories';
	$database->query($query);
	$query = 'TRUNCATE TABLE portals';
	$database->query($query);
	$query = 'TRUNCATE TABLE categories';
	$database->query($query);
	$query = 'TRUNCATE TABLE articles';
	$database->query($query);
	$query = 'SET FOREIGN_KEY_CHECKS = 1';
	$database->query($query);
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
	cleanDatabase($database);
	while ($reader->name === 'page') {
		$page = simplexml_import_dom($doc->importNode($reader->expand(), true));
		$reader->next('page');
		$title = (string) $page->title;
		$categories = getCategories($page->revision->text);
		$portals = getPortals($page->revision->text);
		saveArticle($database, $title, $categories, $portals);
	}
}
