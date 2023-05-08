<?php
$toArray = function($x) use(&$toArray)
{
    return (is_scalar($x) || is_null($x))
        ? $x
        : array_map($toArray, (array) $x);
};

$table_info_list = array();

if(is_dir(__DIR__ . '/tables')){
    $dir = __DIR__ . '/tables';
    $handle = opendir($dir);
    $files = array();
    while(false !== ($basename = readdir($handle))){
        if($basename === "." || $basename === ".."){
            continue;
        }else{
            $file_path = $dir."/".$basename;
            if(is_file($file_path)){
                $pathparts = pathinfo($file_path);
                $filename = $pathparts['filename'];
                $extension = $pathparts['extension'];
                if($pathparts['extension'] === 'json'){
                    $file_content = file_get_contents($file_path);
                    $table_data = $toArray(json_decode($file_content));
                    $table_info_list[$filename] = $table_data;
                }
            }
        }
    }
    closedir($handle);
}

$config['table_info_list'] = $table_info_list;