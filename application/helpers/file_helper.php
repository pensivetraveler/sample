<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// 확장자 추출
function fn_getFileExt($filename)
{
    return substr($filename,strrpos($filename,".")+1);
}

// 이미지 파일 확장자인지 확인
function fn_isImageFileExt($fileext)
{
    $b = false;

    $fileext = strtolower($fileext);
    if ($fileext == "jpg" || $fileext == "gif" || $fileext == "png") $b = true;

    return $b;
}

// byte 단위의 파일크기를 KB로 변환
// 크기가 1MB 이상이 되면 소수점을 생략하고
// 그이하일 경우는 소수점 둘째자리까지 출력한다.
function getFileSizeToKB($filesize)
{
    $intTemp = "";
    $arrTemp = array();

    $intTemp = round($filesize/1024,2);
    $arrTemp = explode(".", $intTemp);

    if (intval($arrTemp[0]) > 999 || intval($arrTemp[1]) == 0) {
        $rtn = number_format($arrTemp[0]);
    } else {
        $rtn = number_format($arrTemp[0].".".$arrTemp[1],2);
    }

    return $rtn;
}

// byte 단위의 파일크기를 MB로 변환
// 크기가 1GB 이상이 되면 소수점을 생략하고
// 그이하일 경우는 소수점 첫째자리까지 출력한다.
function getFileSizeToMB($filesize)
{
    $intTemp = "";
    $arrTemp = array();

    $intTemp = round($filesize/1024/1024,2);
    $arrTemp = explode(".", $intTemp);

    if (intval($arrTemp[0]) > 999 || intval($arrTemp[1]) == 0) {
        $rtn = number_format($arrTemp[0]);
    } else {
        $rtn = number_format($arrTemp[0].".".$arrTemp[1],1);
    }

    return $rtn;
}

// link 상 파일 존재하는지 여부 확인하기
function does_url_exists($url)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($code == 200) {
        $status = true;
    } else {
        $status = false;
    }
    curl_close($ch);
    return $status;
}