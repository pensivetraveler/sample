<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Include PHP Spreadsheet library files
require_once APPPATH.'third_party/PHPSpreadSheet/Spreadsheet.php';

class Php_spreadsheet_lib extends Spreadsheet
{
    public function __construct()
    {
        log_message('Debug', 'PHP Spreadsheet RestApi class is loaded.');
    }

    public function load()
    {
        $SpreadSheet = new Spreadsheet();

        return $SpreadSheet;
    }
}
