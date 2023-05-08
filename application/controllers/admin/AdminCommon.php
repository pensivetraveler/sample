<?php defined('BASEPATH') OR exit('No direct script access allowed');

include __DIR__.'/../Common.php';

class AdminCommon extends Common
{
    public $first_member_in_group_yn = false;

    function __construct()
    {
        parent::__construct();

        $this->title = '';
        $this->path = 'admin';

        $this->model_prefix = ($this->path !== '')?"Model_{$this->path}":"";
        $this->model_path = ($this->path !== '')?"{$this->path}/":"";
        $this->view_path = ($this->path !== '')?"{$this->path}/":"";
        $this->url_path = ($this->path !== '')?"/{$this->path}":"";
        $this->assets_path = "/public/".(($this->path !== '')?"{$this->path}":"");

        $this->paging = array(
            'total_count' => 0,
            'start_index' => 1,
            'unit_counts' => 12,
            'total_pages' => 0,
            'count_start' => 0,
            'current_url' => "{$this->url_path}/{$this->class}",
        );

        if($this->session->userdata('MEMBER_NICKNAME')){
            $member_nickname = $this->session->userdata('MEMBER_NICKNAME');
            $member_code = substr($member_nickname, -2);
            if((int) $member_code === 1){
                $this->first_member_in_group_yn = true;
            }
        }

        // uploads 폴더 없을 시, 이를 생성
        if(@mkdir(ABS_PATH."/uploads/", 0777)) {
            if(is_dir(ABS_PATH."/uploads/")) {
                @chmod(ABS_PATH."/uploads/", 0777);
            }
        }
    }

    public function index()
    {
        echo $this->router->class;
    }

    protected function get_filters($dto)
    {
        $data = array();
        $data['approve_yn'] = $this->{$this->model_filter}->get_approve_yn();
        $data['del_yn'] = $this->{$this->model_filter}->get_del_yn();
        if($this->router->method == 'detail'){
            $data['user_input'] = $this->get_user_input_filters();
        }

        return $data;
    }

    // TODO response_admin 을 모두 procframe_admin으로 변경
    protected function response_admin($code, $msg = "", $data = array(), $type = 'string')
    {
        switch ($code) {
            case -2 :
                $msg = "서버 오류 : {$this->class}/{$msg}";
                $this->response_alert($code, $msg, $data, $type);
                break;
            case -99 :
                $msg = "test : {$this->class}/{$msg}";
                $this->response_alert($code, $msg, $data, $type);
                break;
            case 1000 :
                $this->response_alert($code, $msg, $data, $type);
                break;
        }
    }

    protected function procframe_admin($data, $mode, $class, $method = '', $callback = '')
    {
        $data['exception_postdata'] = $data;
        $data['exception_mode'] = $mode;
        $data['exception_gourl'] = "{$this->url_path}/{$class}/{$method}";
        if(!isEmpty($callback)){
            $data['exception_callback'] = "parent.{$callback}";
        }

        $this->load->view('common/exception_handler', $data);
    }
}