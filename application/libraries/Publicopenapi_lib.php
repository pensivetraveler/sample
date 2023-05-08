<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Publicopenapi_lib
{
    public function __construct()
    {
        log_message('Debug', 'Public Open API class is loaded.');
    }

    public function load($className, $serviceKey)
    {
        // Include Public API third-party files
        require_once APPPATH."third_party/PublicOpenAPI/{$className}/RestApi.php";

        $restApi = new RestApi($serviceKey);

        return $restApi;
    }
}
