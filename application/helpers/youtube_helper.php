<?php

function get_youtube_id($link)
{
    if(strpos($link, '?') !== false){
        $arr_1 = explode('?', $link);
        $arr_2 = explode('=', $arr_1[1]);
        $id = $arr_2[1];
    }else{
        $arr_1 = explode('/', $link);
        $cnt = count($arr_1)-1;
        $id = $arr_1[$cnt];
    }

    return $id;
}
