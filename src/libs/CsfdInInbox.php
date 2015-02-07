<?php

class CsfdInInbox
{
    public function isMovie($articleText)
    {
        return preg_match('~{{Infobox - film~', $articleText) === 1;
    }


    public function existsCsfdInformationInInbox($articleText)
    {
        return preg_match('~\| čsfd =\s*[0-9]+~', $articleText) === 1;
    }


    public function existsCsfdInformationInHyperlinks($articleText)
    {
        return preg_match('~{{Čsfd film\|id=[0-9]+}}~', $articleText) === 1;
    }


    public function getCsfdId($articleText)
    {
        preg_match('~{{Čsfd film\|id=([0-9]+)}}~', $articleText, $matches);
        return $matches[1];
    }
}
