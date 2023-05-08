<?php defined('BASEPATH') OR exit('No direct script access allowed');

include __DIR__ . '/AppCommon.php';

class Index extends AppCommon
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