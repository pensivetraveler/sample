<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// 문자열 자르기
function cutstr($str,$len,$tail)
{
    return mb_strimwidth($str, 0, $len, $tail,"UTF-8");
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

function getPaging($paging)
{
    $totalCount = $paging['total_count'];
    $totalPages = $paging['start_index'];
    $startIndex = $paging['total_pages'];
    $currentUrl = ($paging['count_start'])?:'/';
    $urlQueries = ($paging['url_queries'])?:'';

    $html = "";

    if($totalCount > 0){
        $html .= "<div id='pagingShell' class='paging-shell'>";
        $html .= "    <div class='page-info'>";
        $html .= "        <span>총 {$totalPages}페이지 중 {$startIndex}번째</span>";
        $html .= "    </div>";
        $html .= "    <div class='page-anchor'>";
        $html .= "        <ul>";

        if($totalPages > 1){
            $prevIndex = ($startIndex > 2) ? $startIndex - 1 : 1;
            $nextIndex = ($startIndex == $totalPages) ? $startIndex : $startIndex+1;

            if($totalPages > 5){
        $html .= "            <li class='btn-prev'><a href='{$currentUrl}?start_index=1{$urlQueries}'><<</a></li>";
            }

        $html .= "            <li class='btn-prev'><a href='{$currentUrl}?start_index={$prevIndex}{$urlQueries}'><</a></li>";

            if($totalPages > 5) {
                if ($startIndex + 1 < 5) {
                    for ($i = 1; $i <= 4; $i++) {
                        $activated = "";
                        if ($i == $startIndex) {
                            $activated = 'is-on';
                        }
        $html .= "            <li class='paging {$activated}'><a href='{$currentUrl}?start_index={$i}{$urlQueries}'>{$i}</a></li>";
                    }
        $html .= "            <li class='paging dots'>...</li>";
        $html .= "            <li class='paging'><a href='{$currentUrl}?start_index={$totalPages}{$urlQueries}'>{$totalPages}</a></li>";
                }
                else{
                    if($totalPages-$startIndex < 4){
        $html .= "            <li class='paging'><a href='{$currentUrl}?start_index=1{$urlQueries}'>1</a></li>";
        $html .= "            <li class='paging dots'>...</li>";
                        for($i = $totalPages-3; $i <= $totalPages; $i++) {
                            $activated = "";
                            if ($i == $startIndex) {
                                $activated = 'on';
                            }
        $html .= "            <li class='paging {$activated}'><a href='{$currentUrl}?start_index={$i}{$urlQueries}'>{$i}</a></li>";
                        }
                    }else{
        $html .= "            <li class='paging'><a href='{$currentUrl}?start_index=1{$urlQueries}'>1</a></li>";
        $html .= "            <li class='paging dots'>...</li>";
                        for($i = $startIndex-1; $i <= $startIndex+1; $i++) {
                            $activated = "";
                            if ($i == $startIndex) {
                                $activated = 'is-on';
                            }
        $html .= "            <li class='paging{$activated}'><a href='{$currentUrl}?start_index={$i}{$urlQueries}'>{$i}</a></li>";
                        }
        $html .= "            <li class='paging dots'>...</li>";
        $html .= "            <li class='paging'><a href='{$currentUrl}?start_index={$totalPages}{$urlQueries}'>{$totalPages}</a></li>";
                    }
                }
            }else{
                for($i = 1; $i <= $totalPages; $i++){
                    $activated = "";
                    if ($i == $startIndex) {
                        $activated = 'is-on';
                    }
        $html .= "            <li class='paging {$activated}'><a href='{$currentUrl}?start_index={$i}{$urlQueries}'>{$i}</a></li>";
                }
            }

        $html .= "            <li class='btn-next'><a href='{$currentUrl}?start_index={$nextIndex}{$urlQueries}'>></a></li>";
            if($totalPages > 5){
        $html .= "            <li class='btn-next'><a href='{$currentUrl}?start_index={$totalPages}{$urlQueries}'>>></li>";
            }
        }
        $html .= "        </ul>";
        $html .= "    </div>";
        $html .= "</div>";
    }

    return $html;
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

// 페이징
function customPaging($total_count, $unit_count, $current_page)
{
    $html = '';
    $i = 0;

    if ($total_count > 0) {
        // 처음 페이지 [Start]
        if (intval($pPage) > 1) {
            $html = $html."<a class=\"pre_end\" href=\"#goFirst\" onclick=\"fn_goPage('1')\"><img src=\"/images/common/btn_pg1_l.gif\" title=\"처음\" alt=\"처음\" /></a>\n";
        } else {
            $html = $html."<a class=\"pre_end off\" href=\"#goFirst\"><img src=\"/images/common/btn_pg1_l_off.gif\" title=\"처음페이지입니다\" alt=\"처음페이지입니다\" /></a>\n";
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
            $html = $html."<a class=\"pre\" href=\"#goPrev\" onclick=\"fn_goPage('".($pPage-1)."')\"><img src=\"/images/common/btn_pg2_l.gif\" title=\"이전 페이지\" alt=\"이전 페이지\" /></a>\n";
        } else {
            $html = $html."<a class=\"pre off\" href=\"#goPrev\"><img src=\"/images/common/btn_pg2_l_off.gif\" title=\"이전 페이지가 없습니다\" alt=\"이전 페이지가 없습니다\" /></a>\n";
        }

        //for i = numstart to numend
        for ($i = $numstart;$i<=$numend;$i++) {
            $attach = "";

            if ($i == $numstart) $attach = " class=\"first-child\"";

            if ($i == intval($pPage)) {
                $html = $html."<strong".$attach.">".$i."</strong>\n";
            } else {
                $html = $html."<a".$attach." href=\"javascript:fn_goPage('".$i."')\">".$i."</a>\n";
            }
        }

        if (intval($pCount) > intval($pPage)) {
            $html = $html."<a class=\"next\" href=\"#goNext\" onclick=\"fn_goPage('".($pPage+1)."')\"><img src=\"/images/common/btn_pg2_r.gif\" title=\"다음 페이지\" alt=\"다음 페이지\" /></a>\n";
        } else {
            $html = $html."<a class=\"next off\" href=\"#goNext\"><img src=\"/images/common/btn_pg2_r_off.gif\" title=\"다음 페이지가 없습니다\" alt=\"다음 페이지가 없습니다\" /></a>\n";
        }

        // 마지막 페이지 [Start]
        if (intval($pCount) > intval($pPage)) {
            $html = $html."<a class=\"next_end\" href=\"#goLast\" onclick=\"fn_goPage('".$pCount."')\"><img src=\"/images/common/btn_pg1_r.gif\" title=\"마지막 페이지\" alt=\"마지막 페이지\" /></a>\n";
        } else {
            $html = $html."<a class=\"next_end off\" href=\"#goLast\"><img src=\"/images/common/btn_pg1_r_off.gif\" title=\"마지막 페이지 입니다\" alt=\"마지막 페이지 입니다\" /></a>\n";
        }
        // 마지막 페이지 [Start]
    }

    return $html;
}