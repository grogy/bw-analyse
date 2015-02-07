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
        $template = preg_match('~{{Čsfd film\|id=[0-9]+}}~', $articleText);
        $hyperLink = preg_match('~www\.csfd\.cz\/film\/[0-9]+~', $articleText);
        return $template === 1 || $hyperLink === 1;
    }


    public function getCsfdId($articleText)
    {
        $result = preg_match('~{{Čsfd film\|id=([0-9]+)}}~', $articleText, $matches);
        if ($result === 1) {
            return $matches[1];
        }
        preg_match('~www\.csfd\.cz\/film\/([0-9]+)~', $articleText, $matches);
        return $matches[1];
    }
}
