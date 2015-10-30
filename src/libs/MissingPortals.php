<?php

use Nette\Database\Connection;

class MissingPortals
{
    const PROPOSAL_TYPE = 3;

    const MAXIMUM_COUNT_ARTICLES = 10000;

    /**
     * @var Connection
     */
    private $database;

    /**
     * @var ProposalImprove
     */
    private $proposalImprove;


    public function __construct(Connection $database, ProposalImprove $proposalImprove)
    {
        $this->database = $database;
        $this->proposalImprove = $proposalImprove;
    }



    public function process()
    {
        $this->proposalImprove->cleanAllProposal(self::PROPOSAL_TYPE);
        $portals = $this->getPortals();
        foreach ($portals as $portal) {
            if ($portal['name'] == 'Filosofie') {
                continue;
            }
            $countArticles = $this->getCountArticlesWithPortal($portal['id']);
            $categoriesPairs = $this->getPairCategoryUsage($portal['id']);
            $i = 0;
            while (true) {
                $articles = $this->getArticleIdsOutOfPortal(self::MAXIMUM_COUNT_ARTICLES * $i++, $portal['id']);
                if (empty($articles)) {
                    break;
                }
                foreach ($articles as $article) {
                    if ($this->hasPortal($article['article_id'], $portal['id'])) {
                        continue;
                    }
                    $articleCategories = $this->getArticleCategories($article['article_id']);
                    if ($this->passToPortal($countArticles, $categoriesPairs, $articleCategories)) {
                        $message = 'Tento článek pravděpodobně patří do portálu ' . $portal['name'] . '.';
                        try {
                            $this->proposalImprove->insertToDatabase($article['article_id'], self::PROPOSAL_TYPE, $message);
                        } catch (PDOException $e) {
                            $this->proposalImprove->addToDatabase($article['article_id'], self::PROPOSAL_TYPE, $message);
                        }
                    }
                }
            }
            unset($articles);
        }
    }



    private function hasPortal($articleId, $portalId)
    {
        $query = '
            SELECT *
            FROM article_portals
            WHERE article_id = ? AND portal_id = ?';
        $result = $this->database->query($query, $articleId, $portalId)->fetch();
        if ($result) {
            return true;
        }
        return false;
    }



    private function getPortals()
    {
        $query = '
            SELECT *
            FROM portals';
        $portals = $this->database->query($query)->fetchAll();
        return $portals;
    }



    private function getArticleIdsOutOfPortal($offset, $portalId)
    {
        $query = '
            SELECT *
            FROM article_portals
            WHERE portal_id != ?
            GROUP BY article_id
            LIMIT ?, ?';
        $articles = $this->database->query($query, $portalId, $offset, self::MAXIMUM_COUNT_ARTICLES)->fetchAll();
        return $articles;
    }



    private function getArticleCategories($articleId)
    {
        $query = '
            SELECT category_id
            FROM article_categories
            WHERE article_id = ?';
        $categories = $this->database->query($query, $articleId)->fetchPairs('category_id', 'category_id');
        return $categories;
    }



    private function getPairCategoryUsage($portalName)
    {
        $query = '
            SELECT ac.category_id, COUNT(ac.article_id) count
            FROM article_categories ac JOIN article_portals ap ON ac.article_id = ap.article_id
            WHERE ap.portal_id = ?
            GROUP BY ac.category_id';
        $categories = $this->database->query($query, $portalName)->fetchPairs('category_id', 'count');
        return $categories;
    }



    private function getCountArticlesWithPortal($portalId)
    {
        $query = '
            SELECT COUNT(ac.category_id) count
            FROM article_categories ac JOIN article_portals ap ON ac.article_id = ap.article_id
            WHERE ap.portal_id = ?';
        $countArticles = $this->database->query($query, $portalId)->fetch();
        return $countArticles['count'];
    }



    private function passToPortal($countArticles, $categoriesPairs, $articleCategories)
    {
        $categoriesIntersect = array_intersect_key($categoriesPairs, $articleCategories);
        $sum = 0;
        foreach ($categoriesIntersect as $key => $val) {
            $sum += count($categoriesIntersect) * $categoriesPairs[$key] / $countArticles;
        }
        if ($sum >= 0.5) {
            return true;
        }
        return false;
    }
}
