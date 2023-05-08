<?php defined('BASEPATH') or exit('No direct script access allowed');

include __DIR__ . '/WebCommon.php';

class Index extends WebCommon
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