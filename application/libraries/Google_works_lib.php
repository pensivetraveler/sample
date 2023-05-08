<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Google_works_lib
{
    public function __construct()
    {
        log_message('Debug', 'Google Spreadsheet RestApi class is loaded.');

        $this->spreadsheetId;
    }

    public function load($className, $id)
    {
        // Include Google library files
        require_once APPPATH."third_party/{$className}/RestApi.php";

        $restApi = new restapi($id);

        return $restApi;
    }
}
