<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

function set_translation_language($lang){
    $lang_path = APPPATH."language/";
    $charset = 'utf-8';
    putenv("LC_ALL=$lang");
    putenv("LC_LANG=$lang");
    putenv("LC_LANGUAGE=$lang");
    $l = explode('_', $lang);
    /* not in all system and not all locales got the classic name this stupid method try to solve it*/
    if(! setlocale(LC_ALL, $lang.".".$charset))
        if(! setlocale(LC_ALL, $lang))
            if(! setlocale(LC_ALL,$l[0].".".$charset))
                setlocale(LC_ALL,$l[0]);
    $domain = 'messages';
    bindtextdomain($domain, $lang_path);
    bind_textdomain_codeset($domain, $charset);
    $str = textdomain($domain);
}
