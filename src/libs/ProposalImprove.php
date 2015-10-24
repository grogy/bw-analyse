<?php

use Nette\Database\Connection;

class ProposalImprove
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
     * Insert notice to database
     * @param int $articleId
     * @param int $type
     * @param string $notice
     * @todo Missing test
     */
    public function insertToDatabase($articleId, $type, $notice)
    {
        $query = '
            INSERT INTO proposal_improve (article_id, notice, type)
            VALUES (?, ?, ?)';
        $this->database->query($query, $articleId, $notice, $type);
    }



    public function addToDatabase($articleId, $type, $notice)
    {
        $query = '
                UPDATE proposal_improve SET notice = CONCAT(notice, " ", ?)
                WHERE article_id = ? AND type = ?';
        $this->database->query($query, $notice, $articleId, $type);
    }


    public function cleanAllProposal($proposalType)
    {
        $query = '
            DELETE FROM proposal_improve
            WHERE type = ?';
        $this->database->query($query, $proposalType);
    }
}
