<?php

function parseInfoBoxFromArticle($articleSource)
{
	$infoBoxName = getInfoBoxName($articleSource);
	$parameters = getInfoBoxParameters($articleSource);
	return new InfoBox($infoBoxName, $parameters);
}


function getInfoBoxName($infoBox)
{
	if (!preg_match("/{{Infobox (?:- )([^\n]+)/", $infoBox, $match)) {
		return '';
	}
	return $match[1];
}


function getInfoBoxParameters($infoBox)
{
	preg_match_all("/[ |\t]*\|[ |\t]*([^=]+)[ |\t]*=[ |\t]*([^\n]+)\n/", $infoBox, $match);
	$results = [];
	for ($i = 0; $i < count($match[0]); $i++) {
		$results[trim($match[1][$i])] = trim($match[2][$i]);
	}
	return $results;
}


function hasInfoBox($articleSource)
{
	if (preg_match('/{{Infobox - film/', $articleSource)) {
		return true;
	}
	return false;
}
