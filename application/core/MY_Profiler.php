<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Profiler extends CI_Profiler
{
    function __construct()
    {
        parent::__construct();
    }

    public function run()
    {
        $output = parent::run();
        // log output here, and optionally return it if you do want it to display
    }
}