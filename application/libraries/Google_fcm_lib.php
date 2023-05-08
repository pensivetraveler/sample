<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Firebase Push Alarm Api Class
 *
 * 구글 파이어베이스 푸시 알람을 위한 API
 *
 * @category    Libraries
 * @author      kw.lee@thefit.io
 * @link        https://thefit.io
 */

class Google_fcm_lib
{
    public function __construct(){
        log_message('Debug', 'Firebase RestApi class is loaded.');

        // api Key
        $this->apiKey = FCM_API_KEY;

        // base Url
        $this->baseUrl = FCM_BASE_URL;
    }

    public function load(){
        // Include Firebase library files
        require_once APPPATH.'third_party/GoogleFCM/RestApi.php';

        $baseUrl = $this->baseUrl;
        $apiKey = $this->apiKey;

        $push = new RestApi($baseUrl, $apiKey);

        return $push;
    }
}
