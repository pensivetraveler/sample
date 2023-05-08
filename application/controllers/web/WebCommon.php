<?php defined('BASEPATH') OR exit('No direct script access allowed');

include __DIR__.'/../Common.php';

class WebCommon extends Common
{
    function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo $this->router->class;
    }

    protected function _view($view, $data="")
    {
        header("Cache-Control: no-cache");

        if($this->method == 'detail' || $this->method == 'myinfo'){
            $data['data'] = makeKeyLower($data['data']);
        }

        $data['page'] = (object) array(
            'title' => $this->title,
            'parent' => $this->parent,
            'router' => $this->class,
            'method' => $this->method,
            'parent_seqno' => $this->parent_seqno,
            'target_seqno' => $this->target_seqno,
        );

        $data['session'] = $this->get_session_value();

        $this->load->view("{$this->view_path}includes/inc_head", $data);
        $this->load->view("{$this->view_path}includes/inc_header", $data);
        $this->load->view("{$this->view_path}includes/inc_leftmenu", $data);
        $this->load->view($view, $data);
        $this->load->view("{$this->view_path}includes/inc_footer", $data);
        $this->load->view("{$this->view_path}includes/inc_modal", $data);
        $this->load->view("{$this->view_path}includes/inc_tail", $data);
    }

    protected function _view_login($view, $array="")
    {
        $array['page'] = (object) array(
            'title' => $this->title,
            'router' => $this->class,
            'method' => $this->method,
        );

        $this->load->view("{$this->view_path}includes/inc_head");
        $this->load->view($view, $array);
        $this->load->view("{$this->view_path}includes/inc_tail");
    }
}