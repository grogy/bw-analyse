<?php

use Nette\Database\Connection;

class EnglishPages
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
     * Get czech and english equivalent pages
     * @todo Missing test
     */
    public function getPairsPages($page)
    {
        $query = '
            SELECT a1.id id, a1.text czech, a2.text english
            FROM articles a1
                JOIN articles_language_association t ON t.article_czech = a1.id
                JOIN articles a2 ON t.article_english = a2.id
            LIMIT ?, ?';
        return $this->database->query($query, $page * 100, 100)->fetchAll();
    }


    public function cleanAllProposal()
    {
        $query = '
            DELETE FROM proposal_improve
            WHERE `type` = 2';
        $this->database->query($query);
    }
}
