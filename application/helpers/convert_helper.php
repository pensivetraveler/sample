<?php
// 글제목에 들어간 tag를 제거함.
function titleFilter($str)
{
    $str = stripslashes($str);
    $str = str_replace("<","&lt;",$str);
    $str = str_replace(">","&gt;",$str);
    return $str;
}

// 글내용에 들어간 tag를 제거함.
// \r\n을 <br/> 로 변경
function contentFilter($str)
{
    //$str = str_replace("<","&lt;",$str);
    //$str = str_replace(">","&gt;",$str);
    $str = preg_replace("/<center>(.*)<\/center>/is","<p style=\"text-align:center\">$1</p>",$str);
    $str = str_replace("\r\n","<br />\r\n",stripslashes($str));

    return $str;
}

// 글내용에 들어간 tag를 제거함. \r\n을 <br/> 로 변경
function commentFilter($str)
{
    $str = str_replace("<","&lt;",$str);
    $str = str_replace(">","&gt;",$str);
    $str = str_replace("\r\n","<br />\r\n",stripslashes($str));

    return $str;
}

// utf8 문자열로 한 글자 단위로 array 형태로
function php_fn_utf8_to_array ($str)
{
    $re_arr = array(); $re_icount = 0;
    for($i=0,$m=strlen($str);$i<$m;$i++){
        $ch = ord($str[$i]);
        if($ch<128){$re_arr[$re_icount++]=substr($str,$i,1);}
        else if($ch<224){$re_arr[$re_icount++]=substr($str,$i,2);$i+=1;}
        else if($ch<240){$re_arr[$re_icount++]=substr($str,$i,3);$i+=2;}
        else if($ch<248){$re_arr[$re_icount++]=substr($str,$i,4);$i+=3;}
    }
    return $re_arr;
}

//utf8문자열을 잘라낸다.
function php_fn_utf8_substr($str,$start,$length=NULL)
{
    return implode('',array_slice(php_fn_utf8_to_array($str),$start,$length));
}

//utf8문자열의 길이를 구한다.
function php_fn_utf8_strlen($str)
{
    return count(php_fn_utf8_to_array($str));
}

//줄임말 만들기
function makeEllipsis($str, $length)
{
    $strLength = php_fn_utf8_strlen($str);
    if($strLength > $length){
        $str = mb_substr($str, 0, $length, 'utf-8');
        $str = $str.'...';
    }

    return $str;
}

// utf를 euc로 변경
function utf2euc($str)
{
    $str = iconv("UTF-8","cp949//IGNORE", $str);

    return $str;
}

//퍼센트 함수
function fnPercent($range, $total, $slice, $decimal = 0)
{
    if($total == 0) {
        //Division by zero 에러방지
        $total = 1;
    }

    $result = 0;

    switch ($range) {
        case 'totalPer' :
            break;
        case 'total' :
            //n = 전체값 * 퍼센트 / 100;
            $metaVal = ($total * $slice) / 100;
            $result = round($metaVal);
            break;
        case 'slice' :
            //n% = 일부값 / 전체값 * 100;
            $metaVal = ($slice / $total) * 100;
            $result = number_format($metaVal, $decimal, '.', '');
            break;
    }

    return $result;
}

// stdClass -> Array 로 변경
function objectToArray($d)
{
    if (is_object($d)) {
        // Gets the properties of the given object
        // with get_object_vars function
        $d = get_object_vars($d);
    }

    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return array_map(__FUNCTION__, $d);
    } else {
        // Return array
        return $d;
    }
}

// Array -> stdClass 로 변경
function arrayToObject($d)
{
    if (is_array($d)) {
        /*
        * Return array converted to object
        * Using __FUNCTION__ (Magic constant)
        * for recursive call
        */
        return (object) array_map(__FUNCTION__, $d);
    } else {
        // Return object
        return $d;
    }
}

// XML -> Array 로 변경
function XML2Array(SimpleXMLElement $parent)
{
    $array = array();

    foreach ($parent as $name => $element) {
        ($node = & $array[$name])
        && (1 === count($node) ? $node = array($node) : 1)
        && $node = & $node[];

        $node = $element->count() ? XML2Array($element) : trim($element);
    }

    return $array;
}

/*
function keysToLower($obj)
{
    $type = (int) is_object($obj) - (int) is_array($obj);
    if ($type === 0) return $obj;
    reset($obj);
    while (($key = key($obj)) !== null)
    {
        $element = keysToLower(current($obj));
        switch ($type)
        {
            case 1:
                if (!is_int($key) && $key !== ($keyLowercase = strtolower($key)))
                {
                    unset($obj->{$key});
                    $key = $keyLowercase;
                }
                $obj->{$key} = $element;
                break;
            case -1:
                if (!is_int($key) && $key !== ($keyLowercase = strtolower($key)))
                {
                    unset($obj[$key]);
                    $key = $keyLowercase;
                }
                $obj[$key] = $element;
                break;
        }
        next($obj);
    }
    return $obj;
}
*/

function keysToLower($data)
{
    if(is_object($data)){
        $result = new stdClass();
        foreach ($data as $key=>$val){
            if(is_object($val) || is_array($val)){
                $val = keysToLower($val);
            }
            $key = strtolower($key);
            $result->{$key} = $val;
        }
    }else{
        if(is_array($data)){
            $result = array();
            foreach($data as $key=>$val){
                if(is_object($val) || is_array($val)){
                    $val = keysToLower($val);
                }
                if(gettype($key) !== 'integer'){
                    $key = strtolower($key);
                }
                $result[$key] = $val;
            }
        }else{
            $result = $data;
        }
    }

    return $result;
}

function format_phone($phone){
    $phone = preg_replace("/[^0-9]/", "", $phone);
    $length = strlen($phone);

    switch($length){
        case 11 :
            return preg_replace("/([0-9]{3})([0-9]{4})([0-9]{4})/", "$1-$2-$3", $phone);
            break;
        case 10:
            return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "$1-$2-$3", $phone);
            break;
        default :
            return $phone;
            break;
    }
}