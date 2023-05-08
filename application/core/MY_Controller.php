<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
    public $format;

    function __construct()
    {
        parent::__construct();

        date_default_timezone_set('Asia/Seoul');

        $this->load->model('MY_Model');

        // 기본 헬퍼 로드
        $this->load_default_helper();

        // 기본 라이브러리 로드
        $this->load_default_library();

        // 기본 라이브러리 로드
        $this->load_default_model();

        $key = bin2hex($this->encryption->create_key(16));
        $this->encryption->initialize(
            array(
                'key' => $key
            )
        );

        // format 체크
        $this->format_check();
    }

    protected function load_default_helper()
    {
        // $this->load->helper('url');
    }

    protected function load_default_library()
    {
        // $this->load->library('email');
    }

    protected function load_default_model()
    {
        // $this->model_common = "{$this->model_prefix}_common";
        // $this->load->model($this->model_common);
    }

    function format_check()
    {
        $this->format = 'html';
        if ($this->input->get_post('format') == 'json' or $this->input->get_post('json')) {
            $this->format = 'json';
        }
    }

    function response($code, $msg = "", $data = array(), $to_object = TRUE)
    {
        $data = keysToLower($data);

        $response = new StdClass();

        if($code == -2){
            $msg .= "\r\n이슈 출처 : {$this->router->method}";
        }
        if($code == -99){
            $msg = "test";
        }

        $response->code = $code;
        $response->msg = $msg;
        if($to_object == TRUE){
            $response->type = 'object';
            $response->data = $data;
        }else{
            $response->type = 'array';
            if(is_object($data)){
                $response->data = array();
                if(!empty($data)){
                    array_push($response->data, $data);
                }
            }else{
                $response->data = $data;
            }
        }

        $this->output
            ->set_status_header(200)
            ->set_content_type('application/json', 'utf-8')
            ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))
            ->_display();
        exit;
    }

    function response_alert($code, $msg, $data, $type)
    {
        $response = new StdClass();
        $response->code = $code;
        $response->msg = $msg;
        $response->type = $type;
        if($type == 'object'){
            $response->data = array();
            if(!empty($data)){
                array_push($response->data, $data);
            }
        }else{
            $response->data = $data;
        }

        $this->_alert_back($response);
    }

    function _alert_back($data)
    {
        header('Content-Type: text/html; charset=UTF-8');

        $data = json_encode($data);

        $script  = "";
        $script .= "<script type='javascript/text'>";
        $script .= "console.log($data);";
        $script .= "alert('error');";
        $script .= "window.location.href = history.go(-1);";
        $script .= "</script>";

        echo $script;
        exit;
    }

    function record_log($code, $type = 'etc', $parent_record_idx = 0, $log_msg = '', $log_etc = '')
    {
        $dto = array();
        $dto['parent_log_idx'] = $parent_record_idx;
        $dto['class'] = $this->router->class;
        $dto['method'] = $this->router->method;
        $dto['type'] = $type;
        $dto['code'] = $code;
        $dto['log_msg'] = $log_msg;
        $dto['log_etc'] = $log_etc;

        switch ($code){
            case 1 :
                $code_msg = 'init';
                break;
            case 2 :
                $code_msg = 'no records';
                break;
            case 1000 :
                $code_msg = 'success';
                break;
            case -1 :
                $code_msg = 'fail';
                break;
            case -2 :
                $code_msg = 'error';
                break;
        }
        $dto['code_msg'] = $code_msg;

        return $this->MY_Model->record_log($dto);
    }

    function web_sendmail($to, $subject, $message)
    {
        $config = array();
        $config['useragent'] = 'CodeIgniter';
        $config['mailpath']  = '/usr/sbin/sendmail';
        $config['protocol']  = 'smtp';
        $config['smtp_host'] = SMTP_HOST;
        $config['smtp_user'] = SMTP_USER;
        $config['smtp_pass'] = SMTP_PASS;
        $config['smtp_port'] = SMTP_PORT;
        $config['mailtype']  = 'html';
        $config['charset']   = 'utf-8';
        $config['newline']   = "\r\n";
        $config['wordwrap']  = TRUE;

        $this->email->initialize($config);

        $this->email->from(FROM_EMAIL, FROM_NAME);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($message);

        $result = $this->email->send();

        return $result;
    }

    function download_excel($data, $title)
    {
        // PHPExcel 라이브러리 로드
        $this->php_excel_lib->load($title, "{$title} 조회 목록");

        // 테이블 헤드
        $this->php_excel_lib->setTableHead($data, $this->excel_key_list);

        // 테이블 바디
        $this->php_excel_lib->setTableBody($data);

        // 엑셀 파일명
        $this->php_excel_lib->setFileName(PLATFORM_NAME_KR." {$title} ".time());

        // 다운로드
        $this->php_excel_lib->download();
    }
}