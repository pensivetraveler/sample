<?php defined('BASEPATH') OR exit('No direct script access allowed');

include __DIR__.'/../Common.php';

class AppCommon extends Common
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo $this->router->class;
    }
}