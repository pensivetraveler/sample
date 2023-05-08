<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class MY_Controller
 * Author : 이광우
 * Create-Date : 2021-02-12
 * Memo : 프레임워크 커스텀 Model
 */
class MY_Model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Seoul');

        $this->load->helper('function');
        $this->load->dbforge();
    }

    function trans_begin($selectDb)
    {
        $this->db = $this->load->database($selectDb, TRUE);

        $this->db->reconnect();

        $this->db->trans_begin();
    }

    //등록, 수정, 삭제 시
    function query($sql, $array = array())
    {
        $this->db->query($sql, $array);
        $query_log = $this->db->last_query();

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            log_message('error', " query :  '$query_log \r\n' ");
            return 0;
        } else {
            $this->db->trans_commit();
            return 1;
        }
    }

    //등록 시 new id가 필요한 경우
    function query_new_key($sql, $array = array())
    {
        $this->db->query($sql, $array);
        $new_key = $this->db->insert_id();
        $query_log = $this->db->last_query();

        if ($this->db->trans_status() == FALSE) {
            $this->db->trans_rollback();
            log_message('error', " query :  '$query_log \r\n' ");
            return 0;
        } else {
            $this->db->trans_commit();
            return $new_key;
        }
    }

    //카운트로 조회 할 때
    function query_cnt($sql, $array = array())
    {
        $result = $this->db->query($sql, $array)->row()->cnt;
        $query_log = $this->db->last_query();
        $this->db->trans_complete();

        log_message('error', "query :  '$query_log \r\n' ");
        return $result;
    }

    //결과 값이 하나 일 때
    function query_row($sql, $array = array())
    {
        $result = $this->db->query($sql, $array)->row();
        $query_log = $this->db->last_query();
        $this->db->trans_complete();

        log_message('error', "query :  '$query_log \r\n' ");
        return $result;
    }

    //결과 값이 하나 일 때 (Array)
    function query_row_array($sql, $array = array())
    {
        $result = $this->db->query($sql, $array)->row_array();
        $query_log = $this->db->last_query();
        $this->db->trans_complete();

        log_message('error', "query :  '$query_log \r\n' ");
        return $result;
    }

    //리스트로 조회 할 때
    function query_result($sql, $array = array())
    {
        $result = $this->db->query($sql, $array)->result();
        $query_log = $this->db->last_query();
        $this->db->trans_complete();

        log_message('error', "query :  '$query_log \r\n' ");
        return $result;
    }

    //리스트로 조회 할 때
    function query_result_array($sql, $array = array())
    {
        $result = $this->db->query($sql, $array)->result_array();
        $query_log = $this->db->last_query();
        $this->db->trans_complete();

        log_message('error', "query :  '$query_log \r\n' ");
        return $result;
    }

    // 트랜잭션 on
    function query_trans_on()
    {
        $this->db->trans_begin();
    }

    // 트랜잭션 rollback
    function query_trans_rollback()
    {
        $this->db->trans_rollback();
    }

    // 트랜잭션 commit
    function query_trans_commit()
    {
        $this->db->trans_commit();
    }
}