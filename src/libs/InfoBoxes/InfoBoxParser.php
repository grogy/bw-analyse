<?php

function parseInfoBoxFromArticle($articleSource)
{
	$infoBoxName = getInfoBoxName($articleSource);
	$parameters = getInfoBoxParameters($articleSource);
	return new InfoBox($infoBoxName, $parameters);
}


function getInfoBoxName($infoBox)
{
	preg_match("/{{Infobox - ([^\n]+)/", $infoBox, $match);
	return $match[1];
}


function getInfoBoxParameters($infoBox)
{
	preg_match_all("/ \| ([^=]+) = ([^\n]+)\n/", $infoBox, $match);
	$results = [];
	for ($i = 0; $i < count($match[0]); $i++) {
		$results[$match[1][$i]] = $match[2][$i];
	}
	return $results;
}
