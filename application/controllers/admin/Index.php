<?php defined('BASEPATH') OR exit('No direct script access allowed');

include __DIR__.'/AdminCommon.php';

class Index extends AdminCommon
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