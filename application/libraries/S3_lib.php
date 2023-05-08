<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter S3 Rest Api Class
 */

class S3_Lib
{
    public function __construct()
    {
        log_message('Debug', 'Naver RestApi class is loaded.');
    }

    public function load(){
        // Include S3 third party files
        require_once APPPATH.'third_party/S3/S3.php';

        $S3 = new S3();

        return $S3;
    }
}
