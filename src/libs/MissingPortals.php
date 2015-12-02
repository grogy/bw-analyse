<?php

use Nette\Database\Connection;

class MissingPortals
{
    const PROPOSAL_TYPE = 3;
    const MAXIMUM_COUNT_ARTICLES = 100;
    const MAGIC_CONSTANT = 0.1;

    /**
     * @var Connection
     */
    private $database;

    /**
     * @var ProposalImprove
     */
    private $proposalImprove;

    private $sumOfArticle;


    public function __construct(Connection $database, ProposalImprove $proposalImprove)
    {
        $this->database = $database;
        $this->proposalImprove = $proposalImprove;
    }



    private function getMagicNumber($article, $portal, $category)
    {
        return 1;
        $query = '
            SELECT COUNT(*) as c
            FROM articles a
                LEFT JOIN article_categories ac ON a.id = ac.article_id
                LEFT JOIN article_portals ap ON a.id = ap.article_id
            WHERE a.id = ? AND ap.portal_id = ? AND ac.category_id = ?';
        $result = $this->database->query($query, $article['id'], $portal['id'], $category['id'])->fetch();
        return $result['c'] / $this->sumOfArticle;
    }



    private function checkArticle($article, $portals, $categories)
    {
        $articleCategories = $this->getCategoriesForArticle($article);
        foreach ($portals as $portal) {
            $pSumHasCategory = 0;
            $pSumHaveNotCategory = 0;
            foreach ($categories as $category) {
                $val = $this->getMagicNumber($article, $portal, $category);
                if (in_array($category, $articleCategories)) {
                    $pSumHasCategory += $val;
                }
                $pSumHaveNotCategory += $val;
            }

            if ($pSumHaveNotCategory == 0) {
                continue;
            }
            $ratio = $pSumHasCategory / $pSumHaveNotCategory;
            echo 'article = ' . $article['id'] . ', portal = ' . $portal['id'] . ' -> ' . $ratio . PHP_EOL;
            if ($ratio > self::MAGIC_CONSTANT) {
                $message = 'Tento článek pravděpodobně patří do portálu ' . $portal['name'] . '.';
                try {
                    $this->proposalImprove->insertToDatabase($article['article_id'], self::PROPOSAL_TYPE, $message);
                } catch (PDOException $e) {
                    $this->proposalImprove->addToDatabase($article['article_id'], self::PROPOSAL_TYPE, $message);
                }
            }
        }
    }



    public function process()
    {
        $this->proposalImprove->cleanAllProposal(self::PROPOSAL_TYPE);
        $this->sumOfArticle = $this->getCountOfArticles();
        $portals = $this->getPortals();
        $categories = $this->getCategories();

        $i = 0;
        while (true) {
            $articles = $this->getArticles(self::MAXIMUM_COUNT_ARTICLES * $i++);
            if (empty($articles)) {
                break;
            }
            foreach ($articles as $article) {
                $this->checkArticle($article, $portals, $categories);
            }
        }
    }



    private function getCountOfArticles()
    {
        $query = '
            SELECT COUNT(*) as c
            FROM articles a';
        $result = $this->database->query($query)->fetch();
        return $result['c'];
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



    private function getCategories()
    {
        $query = '
            SELECT *
            FROM categories';
        $categories = $this->database->query($query)->fetchAll();
        return $categories;
    }



    private function getCategoriesForArticle($article)
    {
        $query = '
            SELECT c.*
            FROM categories c RIGHT JOIN article_categories ac ON c.id = ac.category_id
            WHERE ac.article_id = ?';
        $categories = $this->database->query($query, $article['id'])->fetchAll();
        return $categories;
    }



    private function getArticles($offset)
    {
        $query = '
            SELECT id
            FROM articles
            LIMIT ?, ?';
        $articles = $this->database->query($query, $offset, self::MAXIMUM_COUNT_ARTICLES)->fetchAll();
        return $articles;
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
        if ($sum >= self::MAGIC_CONSTANT) {
            return true;
        }
        return false;
    }
}
