<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Dreamline Api Class
 *
 * 드림라인 문자 전송을 위한 API
 *
 * @category    Libraries
 * @author      kw.lee@thefit.io
 * @link        https://thefit.io
 */

class Dreamline_lib
{
    public function __construct(){
        log_message('Debug', 'Firebase RestApi class is loaded.');
        $this->base_url = DREAMLINE_BASE_URL;
        $this->auth_key = DREAMLINE_AUTH_KEY;
        $this->id_type = DREAMLINE_ID_TYPE;
        $this->id = DREAMLINE_ID;
        $this->callback_number = DREAMLINE_CALLBACK_NUMBER;
    }

    public function load($test = false){
        // Include Firebase library files
        require_once APPPATH.'third_party/Dreamline/RestApi.php';

        $base_url = $this->base_url;
        $auth_key = $this->auth_key;
        $id_type = $this->id_type;
        $id = $this->id;
        $callback_number = $this->callback_number;

        $RestApi = new RestApi($base_url, $auth_key, $id_type, $id, $callback_number, $test);

        return $RestApi;
    }
}