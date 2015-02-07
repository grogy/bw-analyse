<?php

use Nette\Database\Connection;

class DatabaseSelect
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
     * @todo missing test
     * @param string $portalName
     * @return array|\Nette\Database\IRow[]
     */
    public function getArticleByPortal($portalName)
    {
        $query = '
            SELECT a.*
            FROM articles a
              JOIN article_portals m ON a.id = m.article_id
              JOIN portals p ON p.id = m.portal_id
            WHERE p.name = ?';
        $articles = $this->database->query($query, $portalName)->fetchAll();
        return $articles;
    }
}
