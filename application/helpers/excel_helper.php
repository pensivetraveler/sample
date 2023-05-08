<?php
function toAlpha($idx){
    $alphabetArr = range('A', 'Z');
    $alphabet = '';
    $tensPlace = ($idx - $idx%26)/26;
    if($tensPlace > 0){
        $alphabet .= $alphabetArr[$tensPlace-1];
    }
    $onesPlace = $idx - 26*$tensPlace;
    $alphabet .= $alphabetArr[$onesPlace];
    return $alphabet;
}

function toNumb($alphabet)
{
    $keyNumb = 0;
    $alphabetArr = range('A', 'Z');
    $strLength = strlen($alphabet);
    for($i = 1; $i < $strLength+1; $i++){
        $targetAlphabet = substr($alphabet, -$i, 1);
        if(in_array($targetAlphabet, $alphabetArr)){
            $keyNumb = $keyNumb+($i-1)*26+array_search($targetAlphabet, $alphabetArr);
        }
    }
    return $keyNumb;
}

function nextAlpah($alphabet)
{
    $currentNumb = toNumb($alphabet);
    $nextNumb = $currentNumb+1;
    $nextAlpha = toAlpha($nextNumb);
    return $nextAlpha;
}

function rangeAlpahbet($startAlphabet, $endAlphabet)
{
    $range = array();
    $startNumb = toNumb($startAlphabet);
    $endNumb = toNumb($endAlphabet);
    for($i = $startNumb; $i <= $endNumb; $i++){
        $range[] = toAlpha($i);
    }

    return $range;
}

function translate_key($key){
    $translate_arr = array (
        // 공통
        'NO' => 'NO',
        'RGST_DATE' => '최초 등록일',
        'UPDT_DATE' => '최종 수정일',
    );

    if(array_key_exists($key, $translate_arr)){
        return $translate_arr[$key];
    }else{
        return $key;
    }
}
