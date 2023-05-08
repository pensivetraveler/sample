<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/Mobile_Detect.php";

class IsMobile extends Mobile_Detect
{
    public function __construct() {
        parent::__construct();
    }
}