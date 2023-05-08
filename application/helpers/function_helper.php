<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// DB 값 입력시 '(작은따옴표) 등을 치환. 기본적으로 변경이 되나 추후 적용되지 않는 서버를 대비 하여 사용
function DBfilter($str)
{
    $str = addslashes($str);
    return $str;
}

// 현재 페이지 경로 출력 (폴더까지만)
function fn_getThisPagePath()
{
    return substr($_SERVER['PHP_SELF'],0,strrpos($_SERVER['PHP_SELF'], "/")+1);
}

// 현재 페이지 전체 경로 출력
function fn_getThisPageUrl()
{
    return $_SERVER['PHP_SELF'];
}

// 현재 페이지 파일명 출력
function fn_getThisPageFile()
{
    return basename($_SERVER['PHP_SELF']);
}

// view prefix 셋팅
function fn_getViewPrefix()
{
    $rtn = "";

    if (fn_isEngPage()) { // 영문 페이지
        if (fn_isMobPage()) {
            $rtn = 'eng_m/';
        } else {
            $rtn = 'eng/';
        }
    } else {
        if (fn_isMobPage()) {
            $rtn = 'kor_m/';
        } else {
            $rtn = 'kor/';
        }
    }

    return $rtn;
}

// 빈값 체크
function isEmpty($obj)
{
    $rtn = true;

    if ((isset($obj) && $obj != null) || (is_string($obj) && $obj != "")) {
        $rtn = false;
    }

    return $rtn;
}

// 빈값 체크
function fn_urlEncode($obj)
{
    $obj = str_replace('%2525', '%', $obj);
    $obj = str_replace('%25', '%', $obj);
    $obj = urldecode($obj);
    $encoded_url = urlencode($obj);
    $new_url = str_replace('%2F', '/', str_replace('%3A', ':', $encoded_url));
    return $new_url;
}

function numToAlpha($num)
{
    return chr(substr("000".($num+65),-3));
}

// 다차원 배열 혹은 object에 대해, key값 존재하는지 확인
function in_array_r($needle, $haystack, $strict = false)
{
    foreach ($haystack as $item) {
        if(is_object($item)){
            $item = objectToArray($item);
        }

        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }

    return false;
}

// 인코딩 확인
function detectEncoding($str)
{
    $encode = array('ASCII', 'UTF-8', 'EUC-KR');
    $str_encode = mb_detect_encoding($str, $encode);
    echo $str_encode;
}

// data check
function printData($data, $json = false, $exit = true)
{
    if($json === true){
        echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }else{
        print_r('<pre>');
        print_r($data);
        print_r('</pre>');
    }
    if($exit == true){
        exit;
    }
}

function email_format_checker($email)
{
    $return =  preg_match("/[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+)*@[0-9a-zA-Z_-]+(\.[0-9a-zA-Z_-]+)+$/", $email);

    return $return;
}

function rand_str()
{
    $characters  = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $rendom_str = "";
    $loopNum = 32;
    while ($loopNum--) {
        $tmp = mt_rand(0, strlen($characters));
        $rendom_str .= substr($characters,$tmp,1);
    }

    return $rendom_str;
}

function rand_color($color_arr)
{
    $color = '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    if($color !== '#ffffff' && $color !== '#FFFFFF' && in_array($color, $color_arr) == false){
        return $color;
    }else{
        rand_color($color_arr);
    }
}

function rand_color_arr($numb)
{
    $color_arr = array();
    for($i = 0; $i < $numb; $i++) {
        $color_arr[] = rand_color($color_arr);
    }
    return $color_arr;
}

function getSiteTitle()
{
    $siteTitle = PLATFORM_NAME_KR;
    return $siteTitle;
}

function getEmailAddress()
{
    $emailAddress = PLATFORM_ADMIN_EMAIL;
    return $emailAddress;
}

function makeVariables($array, $keyName)
{
    if(array_key_exists($keyName, $array)){
        foreach ($array[$keyName] as $key=>$val){
            $key = strtolower($key);
            $array[$key] = $val;
        }
    }

    return $array;
}

function makeKeyLower($target)
{
    foreach ($target as $key=>$val) {
        if(gettype($key) !== 'integer') {
            if (gettype($target) == 'object') {
                if (gettype($target->{$key}) == 'object' || gettype($target->{$key}) == 'array') {
                    if(!property_exists($target, strtolower($key))){
                        $newKey = strtolower($key);
                        $target->{$newKey} = $val;
                        unset($target->{$key});
                        makeKeyLower($target->{$newKey});
                    }
                } else {
                    if(!property_exists($target, strtolower($key))){
                        $newKey = strtolower($key);
                        $target->{$newKey} = $val;
                        unset($target->{$key});
                    }
                }
            }
            if (gettype($target) == 'array') {
                if (gettype($target[$key]) == 'object' || gettype($target[$key]) == 'array') {
                    if(!property_exists($target, strtolower($key))){
                        $newKey = strtolower($key);
                        $target->{$newKey} = $val;
                        unset($target->{$key});
                        makeKeyLower($target[$key]);
                    }
                } else {
                    if(!array_key_exists(strtolower($key), $target)){
                        $newKey = strtolower($key);
                        $target[$newKey] = $val;
                        unset($target[$key]);
                    }
                }
            }
        }
    }

    return $target;
}

function mb_basename($path)
{
    $path_arr = explode('/', $path);

    $data = end($path_arr);

    return $data;
}