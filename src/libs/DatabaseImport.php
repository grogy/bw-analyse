<?php

use Nette\Database\Connection;

class DatabaseImport
{
    /**
     * @var Connection
     */
    private $database;


    public function __construct(Connection $database)
    {
        $this->database = $database;
    }


    /**
     * Import data with articles to database
     * @todo missing test
     * @param string $pathToXml path to XML with articles
     */
    public function importPages($pathToXml)
    {
        $doc = new DOMDocument;
        $reader = new XMLReader();
        $reader->open($pathToXml);
        while ($reader->read() && $reader->name !== 'page'); // jump to page elements
        $this->cleanDatabase();
        while ($reader->name === 'page') {
            $page = simplexml_import_dom($doc->importNode($reader->expand(), true));
            $reader->next('page');
            $title = (string) $page->title;
            $categories = $this->getCategories($page->revision->text);
            $portals = $this->getPortals($page->revision->text);
            $this->saveArticle($title, $categories, $portals);
        }
    }


    /**
     * Get categories from Wikipedia page text
     * @param string $text
     * @return array
     */
    public function getCategories($text)
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
    public function getPortals($text)
    {
        $portals = [];
        preg_match_all('/{{(Portály|Portal)\|([^}]+)}}/', $text, $matches);
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
     * @param string $title title of article
     * @param array $categories categories of article
     * @param array $portals portals of article
     */
    private function saveArticle($title, array $categories, array $portals)
    {
        $query = 'INSERT INTO articles (name) VALUES (?)';
        $this->database->query($query, $title);
        $articleId = $this->database->getInsertId();
        foreach ($categories as $category) {
            $categoryId = $this->getCategoryId($category);
            $query = 'INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)';
            $this->database->query($query, $articleId, $categoryId);
        }
        foreach ($portals as $portal) {
            $portalId = $this->getPortalId($portal);
            $query = 'INSERT INTO article_portals (article_id, portal_id) VALUES (?, ?)';
            $this->database->query($query, $articleId, $portalId);
        }
    }


    /**
     * Get category ID
     * If isn't category save in database then save and return ID.
     * @todo missing test
     * @param string $categoryName name of category
     * @return int
     */
    private function getCategoryId($categoryName)
    {
        $query = 'SELECT id FROM categories WHERE name = ?';
        $categoryId = $this->database->query($query, $categoryName)->fetchField('id');
        if (empty($categoryId)) {
            $query = 'INSERT INTO categories (name) VALUES (?)';
            $this->database->query($query, $categoryName);
            $categoryId = $this->database->getInsertId();
        }
        return $categoryId;
    }


    /**
     * Get portal ID
     * If isn't portal save in database then save and return ID.
     * @todo missing test
     * @param string $portalName name of portal
     * @return int
     */
    private function getPortalId($portalName)
    {
        $query = 'SELECT id FROM portals WHERE name = ?';
        $portalId = $this->database->query($query, $portalName)->fetchField('id');
        if (empty($portalId)) {
            $query = 'INSERT INTO portals (name) VALUES (?)';
            $this->database->query($query, $portalName);
            $portalId = $this->database->getInsertId();
        }
        return $portalId;
    }


    /**
     * Clean records from database
     * @todo missing test
     */
    private function cleanDatabase()
    {
        $query = 'SET FOREIGN_KEY_CHECKS = 0';
        $this->database->query($query);
        $query = 'TRUNCATE TABLE article_portals';
        $this->database->query($query);
        $query = 'TRUNCATE TABLE article_categories';
        $this->database->query($query);
        $query = 'TRUNCATE TABLE portals';
        $this->database->query($query);
        $query = 'TRUNCATE TABLE categories';
        $this->database->query($query);
        $query = 'TRUNCATE TABLE articles';
        $this->database->query($query);
        $query = 'SET FOREIGN_KEY_CHECKS = 1';
        $this->database->query($query);
    }
}
