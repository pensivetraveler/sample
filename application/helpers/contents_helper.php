<?php
// 문자열 자르기
function cutstr($str,$len,$tail)
{
    return mb_strimwidth($str, 0, $len, $tail,"UTF-8");
}

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

// 게시판 페이징 (홈페이지용)
// (페이지 갯수, 블록사이즈, 현재페이지)
function getCommPaging($pCount, $bSize, $pPage)
{
    $str = '';
    $i = 0;

    if ($pCount > 0) {
        // 처음 페이지 [Start]
        if (intval($pPage) > 1) {
            $str = $str."<a class=\"pre_end\" href=\"#goFirst\" onclick=\"fn_goPage('1')\"><img src=\"/images/common/btn_pg1_l.gif\" title=\"처음\" alt=\"처음\" /></a>\n";
        } else {
            $str = $str."<a class=\"pre_end off\" href=\"#goFirst\"><img src=\"/images/common/btn_pg1_l_off.gif\" title=\"처음페이지입니다\" alt=\"처음페이지입니다\" /></a>\n";
        }
        // 처음 페이지 [End]

        $numstart = '';
        $numend = '';
        $numstart = (intval( ($pPage-1) / $bSize ) * $bSize) + 1;
        if ($numstart < 1) $numstart = 1;
        $numend = $numstart + $bSize - 1;

        if (intval($pCount) <= intval($numend)) {
            $numend = $pCount;
        }

        //if (intval($pPage) > intval($bSize)) {
        if (intval($pPage) > 1) {
            //$str = $str."<a class=\"goPrev\" href=\"#goPrev\" title=\"이전 ".$bSize."페이지\" onclick=\"fn_goPage('".($numstart-1)."')\>◀</a>\n";
            $str = $str."<a class=\"pre\" href=\"#goPrev\" onclick=\"fn_goPage('".($pPage-1)."')\"><img src=\"/images/common/btn_pg2_l.gif\" title=\"이전 페이지\" alt=\"이전 페이지\" /></a>\n";
        } else {
            //$str = $str."<a class=\"goPrev off\" href=\"#goPrev\" title=\"이전 ".$bSize."페이지가 없습니다\" >◀</a>\n";
            $str = $str."<a class=\"pre off\" href=\"#goPrev\"><img src=\"/images/common/btn_pg2_l_off.gif\" title=\"이전 페이지가 없습니다\" alt=\"이전 페이지가 없습니다\" /></a>\n";
        }

        //for i = numstart to numend
        for ($i = $numstart;$i<=$numend;$i++) {
            $attach = "";

            if ($i == $numstart) $attach = " class=\"first-child\"";

            if ($i == intval($pPage)) {
                $str = $str."<strong".$attach.">".$i."</strong>\n";
            } else {
                $str = $str."<a".$attach." href=\"javascript:fn_goPage('".$i."')\">".$i."</a>\n";
            }
        }

        //if (intval($pCount) > intval($numend)) {
        if (intval($pCount) > intval($pPage)) {
            //$str = $str."<a class=\"goNext\" href=\"#goNext\" onclick=\"fn_goPage('".($numend+1)."')\" title=\"다음 ".$bSize."페이지\">▶</a>\n";
            $str = $str."<a class=\"next\" href=\"#goNext\" onclick=\"fn_goPage('".($pPage+1)."')\"><img src=\"/images/common/btn_pg2_r.gif\" title=\"다음 페이지\" alt=\"다음 페이지\" /></a>\n";
        } else {
            //$str = $str."<a class=\"goNext off\" href=\"#goNext\" title=\"다음 ".$bSize."페이지가 없습니다\">▶</a>\n";
            $str = $str."<a class=\"next off\" href=\"#goNext\"><img src=\"/images/common/btn_pg2_r_off.gif\" title=\"다음 페이지가 없습니다\" alt=\"다음 페이지가 없습니다\" /></a>\n";
        }

        // 마지막 페이지 [Start]
        if (intval($pCount) > intval($pPage)) {
            $str = $str."<a class=\"next_end\" href=\"#goLast\" onclick=\"fn_goPage('".$pCount."')\"><img src=\"/images/common/btn_pg1_r.gif\" title=\"마지막 페이지\" alt=\"마지막 페이지\" /></a>\n";
        } else {
            $str = $str."<a class=\"next_end off\" href=\"#goLast\"><img src=\"/images/common/btn_pg1_r_off.gif\" title=\"마지막 페이지 입니다\" alt=\"마지막 페이지 입니다\" /></a>\n";
        }
        // 마지막 페이지 [Start]
    }

    return $str;
}

// 모바일페이지 체크
function fn_isMobPage()
{
    return (strpos($_SERVER["HTTP_HOST"], "m.") === 0 || strpos($_SERVER["HTTP_HOST"], "engm.") === 0);
}

// 영문페이지 체크
function fn_isEngPage()
{
    return (strpos($_SERVER["HTTP_HOST"], "eng.") === 0 || strpos($_SERVER["HTTP_HOST"], "engm.") === 0);
}

// utf8 문자열로 한 글자 단위로 array 형태로
function php_fn_utf8_to_array ($str)
{
    $re_arr = array(); $re_icount = 0;
    for($i=0,$m=strlen($str);$i<$m;$i++){
        $ch = ord($str{$i});
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