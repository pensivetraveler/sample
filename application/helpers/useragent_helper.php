<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function user_agent()
{
    $iPod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
    $iPhone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $iPad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
    $android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
    //file_put_contents('./public/upload/install_log/agent',$_SERVER['HTTP_USER_AGENT']);
    if($iPad||$iPhone||$iPod){
        return 'ios';
    }else if($android){
        return 'android';
    }else{
        return 'pc';
    }
}

// 모바일 체크
function fn_isMobile($isIncludeTablet=true)
{
    $mobileKeyWords = array("iPhone", "iPod", "BlackBerry", "Android", "Windows CE", "LG", "MOT", "SAMSUNG", "SonyEricsson","Windows Phone");

    if ($isIncludeTablet == null) $isIncludeTablet = true;

    if ($isIncludeTablet) {
        $mobileKeyWords[sizeof($mobileKeyWords)] = "iPad";
    }

    $rtn = false;
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    for ($i=0;$i<sizeof($mobileKeyWords);$i++) {
        if (strpos($user_agent, strtolower($mobileKeyWords[$i])) > -1) {
            //echo strtolower($mobileKeyWords[$i]).", ".strpos($user_agent, strtolower($mobileKeyWords[$i]))."<br />";
            $rtn = true;
            break;
        } else {
            $rtn = false;
        }
    }

    return $rtn;
}

// 모바일 체크
function isMobile()
{
    //Check Mobile
    $mAgent = array("iPhone","iPod","Android","Blackberry", "Opera Mini", "Windows ce", "Nokia", "sony" );
    $chkMobile = false;
    for($i=0; $i<sizeof($mAgent); $i++){
        if(stripos( $_SERVER['HTTP_USER_AGENT'], $mAgent[$i] )){
            $chkMobile = true;
            break;
        }
    }

    return $chkMobile;
}

// ie 체크
function is_ie()
{
    if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false) return true; // IE8
    if(strpos($_SERVER['HTTP_USER_AGENT'], 'Windows NT 6.1') !== false) return true; // IE11
    return false;
}

function getBrowser() {
    $broswerList = array('MSIE', 'Chrome', 'Firefox', 'iPhone', 'iPad', 'Android', 'PPC', 'Safari', 'Trident', 'none');
    $browserName = 'none';

    foreach ($broswerList as $userBrowser){
        if($userBrowser === 'none') break;
        if(strpos($_SERVER['HTTP_USER_AGENT'], $userBrowser)) {
            $browserName = $userBrowser;
            break;
        }
    }
    return $browserName;
}

function isBlockBrowser() {
    $BrowserName = getBrowser();
    if($BrowserName === 'MSIE'||$BrowserName === 'Trident'||$BrowserName === 'iPhone'||$BrowserName === 'iPad'){
        return true;
    }else{
        return false;
    }
}