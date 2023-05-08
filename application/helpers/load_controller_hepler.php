<?php
if (!function_exists('load_controller'))
{
    function load_controller($controller, $method = 'index')
    {
        require_once(FCPATH . APPPATH . 'controllers/' . $controller . '.php');

        $controller = new $controller();

        return $controller->$method();
    }
}