<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 웹사이트에서 사용되는 함수 정의 파일
 */
// 개별 페이지 접근 제한
function ValidPathCheck()
{
    // 개별 페이지 접근 제한용 변수 선언
    $SERVER_NAME = $_SERVER["SERVER_NAME"];
    $HTTP_REFERER =& $_SERVER["HTTP_REFERER"];
    $SERVER_NAME_PREV = "";
    $PREV_URL = "";
    $PATH_INFO = $_SERVER["SCRIPT_NAME"];

    if ($HTTP_REFERER) {
        $SERVER_NAME_PREV = substr($HTTP_REFERER, 7);
        if (strpos($SERVER_NAME_PREV,"?") > 0) {
            // QueryString 이 있다면 제거
            $SERVER_NAME_PREV = substr($SERVER_NAME_PREV, 0, strpos($SERVER_NAME_PREV, "?"));
        }
        // Set PREV_URL [Start]
        $PREV_URL = substr($SERVER_NAME_PREV, strpos($SERVER_NAME_PREV, "/"));
        // Set PREV_URL [End]
        if (strpos($SERVER_NAME_PREV, "/") > 0) {
            // SCRIPT_NAME 이 있다면 제거
            $SERVER_NAME_PREV = substr($SERVER_NAME_PREV, 0, strpos($SERVER_NAME_PREV, "/"));
        }
    }
}

// 카운터 숫자 증가
function execCounter()
{
    include_once $_SERVER[DOCUMENT_ROOT]."/include/dbconn.php";
    $c_url = $_SERVER["REQUEST_URI"];
    $c_ipaddr = $_SERVER["REMOTE_ADDR"];
    $c_referer = $_SERVER["HTTP_REFERER"];
    $c_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $c_agent = $_SERVER["HTTP_USER_AGENT"];

    if ($c_ipaddr != "183.96.1.110") { // 개발자 ip는 카운터에서 제외
        $sql = "INSERT INTO TB_COUNTER (c_url, c_ipaddr, c_referer, c_language, c_agent, rgst_date) VALUES ('$c_url', '$c_ipaddr', '$c_referer','$c_language','$c_agent', now()) ";
        DBquery($sql);
    }
}

// 접속자의 아이피 주소 반환
function getIPaddr()
{
    return $_SERVER["REMOTE_ADDR"];
}

function isLogin()
{
    $b = false;

    if (getSessionValue('USER_SEQNO')) $b = true;

    return $b;
}

function loginCheck()
{
    if (!isLogin()) {
        $html  = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<script type="text/javascript">';
        $html .= '   alert("로그인후 이용 가능합니다.");';
        $html .= '   parent.location.href = "/";';
        $html .= '</script>';

        echo $html;
        exit;
    }
}

function isAdmin()
{
    $b = false;
    if (getSessionValue("isAdmin")) $b = true;

    return $b;
}

function adminCheck()
{
    if (!isAdmin()) {
        $html  = '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        $html .= '<script type="text/javascript">';
        $html .= '   alert("관리자만 접근 가능합니다.");';
        $html .= '   parent.location.href = "/admin";';
        $html .= '</script>';

        echo $html;
        exit;
    }
}

function isAdminLogin()
{
    return (isAdmin() && isLogin());
}

function insertLogLogin($ll_u_id)
{
    include_once $_SERVER["DOCUMENT_ROOT"]."/include/dbconn.php";
    $ll_ipaddr = getIPaddr();
    $ll_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $ll_agent = $_SERVER["HTTP_USER_AGENT"];
    $sql = "INSERT INTO TB_LOGLOGIN (ll_u_id, ll_ipaddr, ll_language, ll_agent, rgst_date) VALUES ('$ll_u_id', '$ll_ipaddr', '$ll_language', '$ll_agent', now())";
    DBquery($sql);
}

function insertAdminLogLogin($al_a_id)
{
    include_once $_SERVER["DOCUMENT_ROOT"]."/include/dbconn.php";
    $al_ipaddr = getIPaddr();
    $al_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];
    $al_agent = $_SERVER["HTTP_USER_AGENT"];
    $sql = "INSERT INTO TB_ADMINLOGLOGIN (al_a_id, al_ipaddr, al_language, al_agent, rgst_date) VALUES ('$al_a_id', '$al_ipaddr', '$al_language', '$al_agent', now())";
    DBquery($sql);
}

function getSessionValue($str)
{
    $ci =& get_instance();

    return $ci->session->userdata($str);
}

// 사용자 사용 금지 아이디 체크
function fn_allowIdCheck($str)
{
    $deny_arr = array(
        "admin",
        "administrator",
        "root"
    );
    $rtn = true;

    foreach ($deny_arr as $v) {
        if ($v == trim($str)) {
            $rtn = false;
        }
    }

    return $rtn;
}

// 랜덤 패스워드 생성
function fn_generateRandomPassword($length=8, $strength=0)
{
    $vowels = 'aeuy';
    $consonants = 'bdghjmnpqrstvz';
    if ($strength & 1) {
        $consonants .= 'BDGHJLMNPQRSTVWXZ';
    }
    if ($strength & 2) {
        $vowels .= "AEUY";
    }
    if ($strength & 4) {
        $consonants .= '23456789';
    }
    if ($strength & 8) {
        $consonants .= '@#$%';
    }

    $password = '';
    $alt = time() % 2;
    for ($i = 0; $i < $length; $i++) {
        if ($alt == 1) {
            $password .= $consonants[(rand() % strlen($consonants))];
            $alt = 0;
        } else {
            $password .= $vowels[(rand() % strlen($vowels))];
            $alt = 1;
        }
    }
    return $password;
}