<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

// 게시판등에 출력할 날짜로 변환 (날짜만)
function printDate($d)
{
    $strtemp = Date("Y.m.d",$d);
    return $strtemp;
}

// 메인 화면 미리보기에 출력할 날짜로 변환 (날짜만)
function printDatePreview($d)
{
    $strtemp = Date("y.m.d",$d);
    return $strtemp;
}

// 게시판등에 출력할 날짜로 변환 (날짜와 시분초까지)
function printDateTime($dt)
{
    $strtemp = Date("Y.m.d H:i:s",$dt);
    return $strtemp;
}

// 게시판등에 출력할 날짜로 변환 (날짜와 시분까지)
function printDateTimeMinute($dt)
{
    $strtemp = Date("Y.m.d H:i",$dt);
    return $strtemp;
}

function makeDatetime($str)
{
    $datetime = "";
    $str = str_replace(' ', '', $str);
    if(strpos($str, '오후') !== false){
        $datetime_arr = explode('오후', $str);
        $date = $datetime_arr[0];
        $date = str_replace('.', '-', $date);
        $time_str = $datetime_arr[1];

        $time_arr = explode(':', $time_str);
        $hour = (int)$time_arr[0] + 12;
        $minute = (int)$time_arr[1];
        $second = (int)$time_arr[2];
        $time = "$hour:$minute:$second";

        $datetime = "$date $time";
    }
    if(strpos($str, '오전') !== false){
        $datetime_arr = explode('오전', $str);
        $date = $datetime_arr[0];
        $date = str_replace('.', '-', $date);
        $time = $datetime_arr[1];

        $datetime = "$date $time";
    }

    return $datetime;
}

function calculate_time_span($start, $end = '')
{
    if($end == ''){
        $end = date('Y-m-d H:i:s');
    }
    $seconds  = strtotime($end) - strtotime($start);

    $months = floor($seconds / (3600*24*30));
    $day = floor($seconds / (3600*24));
    $hours = floor($seconds / 3600);
    $mins = floor(($seconds - ($hours*3600)) / 60);
    $secs = floor($seconds % 60);

    if($seconds < 60)
        $time = "{$secs} 초";
    else if($seconds < 60*60 )
        $time = "{$mins} 분 {$secs} 초";
    else if($seconds < 24*60*60)
        $time = "{$hours} 시간 {$mins} 분 {$secs} 초";
    else if($seconds < 24*60*60)
        $time = "{$day} 일 {$hours} 시간 {$mins} 분 {$secs} 초";
    else
        $time = "{$months} 개월 {$day} 일 {$hours} 시간 {$mins} 분 {$secs} 초";

    return $time;
}

//PARA: Date Should In YYYY-MM-DD Format
//RESULT FORMAT:
// '%y Year %m Month %d Day %h Hours %i Minute %s Seconds'      =>  1 Year 3 Month 14 Day 11 Hours 49 Minute 36 Seconds
// '%y Year %m Month %d Day'                                    =>  1 Year 3 Month 14 Days
// '%m Month %d Day'                                            =>  3 Month 14 Day
// '%d Day %h Hours'                                            =>  14 Day 11 Hours
// '%d Day'                                                     =>  14 Days
// '%h Hours %i Minute %s Seconds'                              =>  11 Hours 49 Minute 36 Seconds
// '%i Minute %s Seconds'                                       =>  49 Minute 36 Seconds
// '%h Hours                                                    =>  11 Hours
// '%a Days                                                     =>  468 Days
function dateDifference($startDate, $endDate, $differenceFormat = '%a' )
{
    $startDateTime = date_create($startDate);
    $endDateTime = date_create($endDate);

    $interval = date_diff($startDateTime, $endDateTime);

    return $interval->format($differenceFormat);

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
