<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|------------------------------------------------------------------------
| Author : 이광우
| Create-Date : 2020-07-01
|------------------------------------------------------------------------
*/

Class Global_function
{
    function _alert($str, $url="")
    {

        header('Content-Type: text/html; charset=UTF-8');

        $script = "<script type=\"text/javascript\">";
        $script .= "alert('" . $str . "');";
        if(!empty($url)) $script .= "location.href='" . $url . "';";
        $script .= "</script>";

        echo $script;
        return;
    }

    function _alert_close($str) {

        header('Content-Type: text/html; charset=UTF-8');

        $script = "<script type=\"text/javascript\">";
        $script .= "alert('" . $str . "');";
        $script .= "self.close();";
        $script .= "</script>";

        echo $script;
        return;
    }

    function dateYmdComma($str_date){
        $date = date("Y.m.d", strtotime( $str_date ) );
        return $date;
    }

    function dateYmdAHiComma($str_date){
        $date = date("Y.m.d A H:i", strtotime( $str_date ) );
        return $date;
    }

    function dateYmdHyphen($str_date){
        $date = date("Y-m-d", strtotime( $str_date ) );
        return $date;
    }

    function dateYear($str_date){
        $date = date("Y", strtotime( $str_date ) );
        return $date;
    }

    function dateMonth($str_date){
        $date = date("m", strtotime( $str_date ) );
        return $date;
    }

    function dateDay($str_date){
        $date = date("d", strtotime( $str_date ) );
        return $date;
    }

    function paging($totalCnt,$pageSize,$pageNum,$fn=""){

        $pagenumber=PAGENUMBER;

        $total_page=ceil($totalCnt/$pageSize);
        $total_block=ceil($total_page/$pagenumber);

        if(($pageNum)% $pagenumber!=0){
            $block=ceil(($pageNum+1)/$pagenumber);
        }else{
            $block=ceil(($pageNum+1)/$pagenumber)-1;
        }
        $first_page=($block-1)*$pagenumber;
        $last_page=$block*$pagenumber;

        $prev=$first_page;
        $next=$last_page+1;
        $go_page=$first_page+1;

        if($fn==""){
            $fn="page_go";
        }



        if($total_block<=$block)
            $last_page=$total_page;

        $page_html="";
        if($totalCnt>0){
            $page_html.="<div class='paging'>";

            if($block>1){
                $page_html.="
					 <span class='prev'>
					 <a href='javascript:".$fn."(1);'><i class='fa fa-angle-double-left'></i></a><a href=javascript:".$fn."($prev);> <i class='fa fa-angle-left'></i> </a>
					 </span>
				";
            }else{
                $page_html.="
					 <span class='prev'>
					 <a href='javascript:".$fn."(1);'><i class='fa fa-angle-double-left'></i></a><a href='#'><i class='fa fa-angle-left'></i></a>
					 </span>
				";
            }

            for($go_page;$go_page<=$last_page;$go_page++){
                if($pageNum==$go_page)
                    $page_html.="<a href=javascript:".$fn."($go_page);  class='on'>$go_page</a>";
                else
                    $page_html.="<a href=javascript:".$fn."($go_page);>$go_page</a>";

            }

            if($block<$total_block){
                $page_html.="
					 <span class='next'>
					 <a href=javascript:".$fn."($next);> <i class='fa fa-angle-right'></i> </a><a href='javascript:".$fn."($total_page);'> <i class='fa fa-angle-double-right'></i> </a>
					 </span>
					";
            }else{
                $page_html.="
					 <span class='next'>
					 <a href='#'><i class='fa fa-angle-right'></i></a><a href='javascript:".$fn."($total_page);'> <i class='fa fa-angle-double-right'></i> </a>
					 </span>
					";

            }
            $page_html.="</div>";
        }else{
            $page_html.="<div class='paging'></div>";
        }

        return $page_html;

    }

    function read_clob($field){

        if(is_null($field)){
            return "";
        }else{
            return $field->read($field->size());
        }
    }

    function textEnter($str){
        $str=str_replace("\n","<br/>",$str);
        return $str;
    }
    public function trimStr($str){
        $str=str_replace(" ","",$str);
        return $str;
    }

    //핸드폰 형식세팅
    function set_phone_number($str){

        if($str){
            $rt = substr($str,0,3)."-".substr($str,3,4)."-".substr($str,7,4);
        }else{
            $rt ="";
        }
        return $rt;
    }


    //날짜
    function change_add_date($date){
        $date =str_replace("-","",$date);
        if($date){
            $rt =substr($date,0,4)."-".substr($date,4,2)."-".substr($date,6,2);
        }else{
            $rt ="";
        }
        return $rt;
    }

    //시간
    function change_add_hm($hm){
        $hs =str_replace(":","",$hm);
        if($hs){
            $rt =substr($hs,0,2).":".substr($hs,2,2);
        }else{
            $rt ="";
        }
        return $rt;
    }


    //날짜
    function change_strip_date($date){
        if($date){
            $rt =str_replace("-","",$date);
        }else{
            $rt="";
        }
        return $rt;
    }

    //시간
    function change_strip_hm($hm){
        if($hm){
            $rt =str_replace(":","",$hm);
        }else{
            $rt ="";
        }

        return $rt;
    }

    //전화번호 '-'기준으로 나누기
    function telnumNoneHypen($str){

        /*
        $tel_num[0] = 전체
        $tel_num[1] = 지역번호or(010/011 ...)
        $tel_num[2] = 중간번호
        $tel_num[3] = 마지막번호
        */
        preg_match('/\(?(?<Num1>\d{2,3})\)?-?\s*(?<Num2>\d{3,4})-?\s*(?<Num3>\d{4})/', $str, $tel_num);
        return $tel_num;
    }

    // DB요일필드를 문자열 요일 필드로, 0123456 => 일월화수목금토
    function dowtostr($dow)
    {
        if (empty($dow) or strlen($dow) >= 7) {
            return '전요일';
        }

        $dow = str_replace('0', '일', $dow);
        $dow = str_replace('1', '월', $dow);
        $dow = str_replace('2', '화', $dow);
        $dow = str_replace('3', '수', $dow);
        $dow = str_replace('4', '목', $dow);
        $dow = str_replace('5', '금', $dow);
        $dow = str_replace('6', '토', $dow);

        return implode(',', str_split($dow, 3)); // 유니코드는 3바이트
    }

    function default_start_date()
    {
        return date('Ymd', strtotime("-1 months"));
    }

    function default_end_date()
    {
        return date('Ymd');
    }

    function escstr($str) {
        $str=str_replace("\r\n","",$str);
        return trim($str);
    }
}
?>
