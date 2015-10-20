<?php

function getListOfDifferences(InfoBox $czechInfoBox, InfoBox $englishInfoBox, $rules)
{
    $differences = [];
    foreach ($rules as $rule) {
        $notice = getNoticeFromRule($czechInfoBox, $englishInfoBox, $rule);
        if ($notice) {
            $differences[] = $notice;
        }
    }
    return $differences;
}


function getNoticeFromRule(InfoBox $czechInfoBox, InfoBox $englishInfoBox, $rule)
{
    switch ($rule['type']) {
        case 'have-to-be-here':
            if (!$czechInfoBox->hasProperty($rule['cs'])) {
                return $rule['notice'];
            }
            break;

        case 'have-to-be-same':
            if ($czechInfoBox->hasProperty($rule['cs']) && $englishInfoBox->hasProperty($rule['en'])) {
                if ($czechInfoBox->getProperty($rule['cs']) != $englishInfoBox->getProperty($rule['en'])) {
                    return $rule['notice'];
                }
            }
            break;

        case 'is-optional-cs':
            if ($englishInfoBox->hasProperty($rule['en']) && !$czechInfoBox->hasProperty($rule['cs'])) {
                return $rule['notice'];
            }
            break;

        default:
            throw new InvalidArgumentException;
    }
    return null;
}
