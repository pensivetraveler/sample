<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Common
 * Author : 이광우
 * Create-Date : 2020-03-29
 * Memo : 관리자 공통 Controller
 */
class Common extends MY_Controller
{
    public $class, $method;
    public $title, $path, $target_seqno, $parent, $parent_seqno;
    public $start_router, $landing_url_path;
    public $model, $model_prefix, $model_path, $view_path, $url_path, $assets_path;
    public $model_common, $model_filter;
    public $media, $category, $attr, $filters, $paging, $append, $filtered;

    public function __construct()
    {
        parent::__construct();

        $this->class = $this->router->class;
        $this->method = $this->router->method;

        $this->title = '';
        $this->path = '';
        $this->target_seqno = '';
        $this->parent = '';
        $this->parent_seqno = '';

        $this->start_router = '';
        $this->landing_url_path = $this->url_path.'/';

        $this->model = "{$this->model_prefix}_{$this->class}";
        $this->model_common = "{$this->model_prefix}_common";
        $this->model_filter = "{$this->model_prefix}_filter";

        $this->format_check();

//        if($this->class !== 'login'){
//            if (!isLogin()) {
//                redirect("{$this->url_path}/login");
//            }
//        }
    }

    protected function login_check()
    {
        $rtn_page = "{$this->url_path}/login";

        if (!isLogin()) {
            $rtn_page = "{$this->url_path}/{$this->start_router}";
        }

        redirect($rtn_page);
    }

    protected function login_check_redirect_to_listing()
    {
        if(isLogin()){
            $this->method = 'listing';
            $this->{$this->method}();
        }else{
            redirect("{$this->url_path}/login");
        }
    }

    protected function make_paging($total_count, $start_index, $url_queries = '')
    {
        $this->paging['total_count'] = $total_count;
        $this->paging['start_index'] = $start_index;
        $this->paging['total_pages'] = ceil($total_count/$this->paging['unit_counts']);
        $this->paging['count_start'] = $total_count - $this->paging['unit_counts']*($start_index-1);
        $this->paging['url_queries'] = $url_queries;
    }

    protected function make_url_queries($dto)
    {
        $url_query_arr = array();
        foreach ($dto as $key=>$value){
            if(array_key_exists($key, $this->filters) && !empty($value)){
                $url_query_arr[$key] = $value;
            }
        }

        if(count($url_query_arr) !== 0){
            return '&'.http_build_query($url_query_arr);
        }else{
            return '';
        }
    }

    protected function get_user_input_filters()
    {
        $data = array();
        $posts = $this->input->post();

        foreach ($posts as $key=>$val){
            if(strpos($key, '_filter')){
                $data[$key] = $val;
            }
        }

        return $data;
    }

    public function code_list($big_cd = "")
    {
        if($this->format == 'html'){
            if($big_cd == ""){
                $data = $this->get_code_list_all();
            }else{
                $data = $this->get_code_list_one($big_cd);
            }

            return $data;
        }else{
            if($big_cd !== ''){
                $data = $this->get_code_list_one($big_cd);

                return $data;
            }else{
                $dto = $this->input->post();

                if($dto['big_cd'] !== ''){
                    $data = $this->get_code_list_one($dto['big_cd']);
                }else{
                    $data = $this->get_code_list_all();
                }

                if(count($data) > 0){
                    $this->response(1000, "코드가 조회되었습니다.", $data, FALSE);
                }else{
                    $this->response(-1, "코드가 존재하지 않습니다.\r\n시스템관리자에게 문의해주세요.\r\n이슈 출처 : {$this->router->method}", $dto);
                }
            }
        }
    }

    protected function get_code_list_all()
    {
        $data = $this->{$this->model_common}->get_code_list_all();

        return $data;
    }

    protected function get_code_list_one($big_cd)
    {
        $data = $this->{$this->model_common}->get_code_list_one($big_cd);

        return $data;
    }

    protected function get_session_value()
    {
        $session = array(
            'SS_USER_SEQNO' => getSessionValue("USER_SEQNO"),
            'SS_USER_NAME' => getSessionValue("MEMBER_NAME"),
            'SS_USER_ID' => getSessionValue("USER_ID"),
            'SS_USER_CD' => getSessionValue("USER_CD"),
            'SS_GROUP_NAME' => getSessionValue("GROUP_NAME"),
            'SS_GROUP_CD' => getSessionValue("GROUP_CD"),
        );

        return $session;
    }

    protected function dto_validate($dto, $case)
    {
        switch ($case){
            case "sign" :
                if($dto['member_email'] == ''){
                    $this->response(-1, "이메일을 입력하세요.", $dto);
                }
                if(email_format_checker($dto['member_email']) == 0){
                    $this->response(-1, "유효하지 않은 이메일 양식입니다.", $dto);
                }
                break;
        }
    }

    protected function revise_dto()
    {
        $get = $this->input->get();
        $post = $this->input->post();

        $dto = array_merge((array)$post, (array)$get);

        if(array_key_exists('filters', $this) &&!isEmpty($this->filters)){
            $dto = array_merge((array)$this->filters, (array)$dto);
        }
        if(array_key_exists('attr', $this) &&!isEmpty($this->attr)){
            $dto = array_merge((array)$this->attr, (array)$dto);
        }
        if(array_key_exists('paging', $this) &&!isEmpty($this->paging)){
            $dto = array_merge((array)$this->paging, (array)$dto);
        }

        return $dto;
    }

    protected function get_file_info($dto)
    {
        $data = $this->{$this->model_common}->get_file_info($dto);

        return $data;
    }

    protected function update_file_path($dto)
    {
        $result = $this->{$this->model_common}->update_file_path($dto);

        return $result;
    }

    function upload_file()
    {
        $upload_path = "/uploads/temp/";

        // temp 폴더 없을 시, 이를 생성
        if(@mkdir(ABS_PATH.$upload_path, 0777)) {
            if(is_dir(ABS_PATH.$upload_path)) {
                @chmod(ABS_PATH.$upload_path, 0777);
            }
        }

        $result = array();

        /* 업로드 셋팅 Start */
        $config = array(
            'allowed_types' => '*',
            'max_size' => 3000,
            'overwrite' => FALSE,
            'encrypt_name' => TRUE,
            'upload_path'   => ABS_PATH.$upload_path
        );
        $this->upload->initialize($config);
        /* 업로드 셋팅 End */

        $number_of_files_uploaded = count($_FILES['upload']['name']);

        for($i = 0; $i < $number_of_files_uploaded; $i++){
            $_FILES['file_each']['name'] = $_FILES['upload']['name'][$i];
            $_FILES['file_each']['type'] = $_FILES['upload']['type'][$i];
            $_FILES['file_each']['tmp_name'] = $_FILES['upload']['tmp_name'][$i];
            $_FILES['file_each']['error'] = $_FILES['upload']['error'][$i];
            $_FILES['file_each']['size'] = $_FILES['upload']['size'][$i];

            /* 업로드 처리 Start */
            if(!$this->upload->do_upload('file_each')){
                $error_msg = $this->upload->display_errors();
            }else{
                $file = $this->upload->data();
                $file["upload_path"] = $upload_path;
                $file["file_seqno"] = $this->{$this->model_common}->insert_file($file);
                $result[] = $file;
            }
            /* 업로드 처리 End */
        }

        if($this->format == 'json'){
            $this->response(1000, 'success', $result, FALSE);
        }else{
            return $result;
        }
    }

    public function download_file()
    {
        $data = null;

        $dto = $this->input->post();

        $file_path = $_SERVER['DOCUMENT_ROOT'].$dto["file_path"];
        $file_size = filesize($file_path);
        $file_name = mb_basename($dto["file_name"]);

        // ie인 경우, 파일명을 euc-kr로 컨버팅
        if( is_ie() ){
            $file_name = utf2euc($file_name);
        }

        header('Content-Description: File Transfer');
        header("Expires: 0");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/force-download");
        header("Content-Disposition: attachment; filename=\"$file_name\"");
        header("Content-Transfer-Encoding: binary");
        header("Content-Length: $file_size");

        readfile($file_path);
    }

    public function listing()
    {
        $dto = $this->revise_dto();

        $data = $this->{"{$this->method}_{$this->class}"}($dto);

        $summary = $this->{"summary_{$this->class}"}($dto);

        if($this->format == 'json'){
            $this->response(1000, "success", $data, FALSE);
        }else{
            if ($this->method == 'listing') {
                $data = array(
                    'dto' => $dto,
                    'filters' => $this->get_filters($dto),
                    'codes' => $this->code_list(),
                    'data' => $data,
                    'paging' => $this->paging,
                    'summary' => $summary,
                );

                $this->_view("{$this->view_path}View_{$this->class}_{$this->method}", $data);
            } else {
                return $data;
            }
        }
    }

    public function detail()
    {
        $dto = $this->revise_dto();

        $detail = $this->{"{$this->method}_{$this->class}"}($dto);

        if($this->format == 'json'){
            $this->response(1000, "success", $detail, TRUE);
        }else{
            $data = array(
                'dto' => $dto,
                'filters' => $this->get_filters($dto),
                'codes' => $this->code_list(),
                'data' => $detail,
                'append' => $this->append,
            );

            $this->_view("{$this->view_path}View_{$this->class}_{$this->method}", $data);
        }
    }

    public function insert()
    {
        $dto = $this->revise_dto();

        $result = $this->{"{$this->method}_{$this->class}"}($dto);

        if($result > 0){
            $dto[$this->target_seqno] = $result;
            if($this->format == 'json'){
                $this->response(1000, "success", $dto, TRUE);
            }else{
                $this->procframe_admin($dto, 'NOALERTANDGO', $this->class, 'detail');
            }
        }else{
            if($this->format == 'json'){
                $this->response(-2, $this->method, $dto, TRUE);
            }else{
                $this->response_admin(-2, $this->method, $dto);
            }
        }
    }

    public function update()
    {
        $dto = $this->revise_dto();

        $result = $this->{"{$this->method}_{$this->class}"}($dto);

        if($result > 0){
            if($this->format == 'json'){
                $this->response(1000, "success", $dto, TRUE);
            }else{
                $this->procframe_admin($dto, 'NOALERTANDGO', $this->class, 'detail');
            }
        }else{
            if($this->format == 'json'){
                $this->response(-2, $this->method, $dto, TRUE);
            }else{
                $this->response_admin(-2, $this->method, $dto);
            }
        }
    }

    public function revoke()
    {
        $dto = $this->revise_dto();

        $result = $this->{"{$this->method}_{$this->class}"}($dto);

        if($result > 0) {
            if($this->format == 'json'){
                $this->response(1000, "success", $dto, TRUE);
            }else{
                $this->procframe_admin(array(), 'NOALERTANDGO', $this->class, 'listing');
                // $this->procframe_admin($dto, 'NOALERTANDGO', $this->class, $dto['method']);
            }
        }else{
            if($this->format == 'json'){
                $this->response(-2, $this->method, $dto, TRUE);
            }else{
                $this->response_admin(-2, $this->method, $dto);
            }
        }
    }

    public function recall()
    {
        $dto = $this->revise_dto();

        $result = $this->{"{$this->method}_{$this->class}"}($dto);

        if($result > 0) {
            if($this->format == 'json'){
                $this->response(1000, "success", $dto, TRUE);
            }else{
                $this->procframe_admin(array(), 'NOALERTANDGO', $this->class, 'listing');
                // $this->procframe_admin($dto, 'NOALERTANDGO', $this->class, $dto['method']);
            }
        }else{
            if($this->format == 'json'){
                $this->response(-2, $this->method, $dto, TRUE);
            }else{
                $this->response_admin(-2, $this->method, $dto);
            }
        }
    }

    public function delete()
    {
        $dto = $this->revise_dto();

        $result = $this->{"{$this->method}_{$this->class}"}($dto);

        if($result > 0) {
            if($this->format == 'json'){
                $this->response(1000, "success", $dto, TRUE);
            }else{
                $this->procframe_admin(array(), 'NOALERTANDGO', $this->class, 'listing');
                // $this->procframe_admin($dto, 'NOALERTANDGO', $this->class, $dto['method']);
            }
        }else{
            if($this->format == 'json'){
                $this->response(-2, $this->method, $dto, TRUE);
            }else{
                $this->response_admin(-2, $this->method, $dto);
            }
        }
    }

    public function excel()
    {
        $dto = $this->revise_dto();
        $this->method = 'listing';

        $list = $this->{"{$this->method}_{$this->class}"}($dto);

        $list_for_excel = array();
        foreach ($list as $no=>$data) {
            $list_for_excel_each = new stdClass();
            $list_for_excel_each->NO = $no+1;
            foreach ($data as $key=>$value){
                if(array_key_exists($key, $this->excel_key_list)){
                    $list_for_excel_each->{$key} = $value;
                }
            }
            array_push($list_for_excel, $list_for_excel_each);
        }

        $this->download_excel($list_for_excel, $this->title.' 이력');
    }
}