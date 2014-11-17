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
