<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Naver Rest Api Class
 *
 * 네이버 연관검색어 추출을 가능하게 하는 클래스
 *
 * @category    Libraries
 * @author      CodexWorld
 * @link        https://www.codexworld.com
 */

class Naver_Lib
{
    public function __construct(){
        log_message('Debug', 'Naver RestApi class is loaded.');

        $this->baseUrl = "https://api.naver.com";
        $this->apiKey = "0100000000b8d24e6c9bce3c11cfce9c4a144f02e372c4bd8d7c26a716d5a14145159f8964";
        $this->secretKey = "AQAAAAC40k5sm848Ec/OnEoUTwLjMqbOKspMsDa+VaduhBAUqw==";
        $this->customerId = 655983;
    }

    public function load(){
        // Include Naver library files
        require_once APPPATH.'third_party/Naver/restapi.php';

        $baseUrl = $this->baseUrl;
        $apiKey = $this->apiKey;
        $secretKey = $this->secretKey;
        $customerId = $this->customerId;

        $naver = new RestApi($baseUrl, $apiKey, $secretKey, $customerId);

        return $naver;
    }
}
