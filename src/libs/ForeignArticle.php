<?php

use Atrox\Matcher;
use Nette\Database\Connection;

class ForeignArticle
{
    /**
     * Read HTML for czech version of article
     * @param string $articleName
     * @return string
     */
    public function getHtml($articleName)
    {
        return file_get_contents('https://cs.wikipedia.org/wiki/' . urlencode($articleName));
    }


    /**
     * Return link for foreign version of article
     * @param string $language language short
     * @param string $html HTML string for parse
     * @return mixed
     */
    public function getLink($language, $html)
    {
        $m = Matcher::single('//a[@hreflang="' . $language . '"]/@href')->fromHtml();
        $link = $m($html);
        return $link;
    }
}
