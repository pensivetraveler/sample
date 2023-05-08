<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter Dreamline Kakao Api Class
 *
 * 드림라인 카카오 전송을 위한 API
 *
 * @category    Libraries
 * @author      kw.lee@thefit.io
 * @link        https://thefit.io
 */

class Dreamline_kakao_lib
{
    public function __construct(){
        log_message('Debug', 'Firebase RestApi class is loaded.');
        $this->base_url = DREAMLINE_BASE_URL;
        $this->auth_key = DREAMLINE_KAKAO_AUTH_KEY;
        $this->id_type = DREAMLINE_KAKAO_ID_TYPE;
        $this->id = DREAMLINE_KAKAO_ID;
        $this->pw = DREAMLINE_KAKAO_PW;
        $this->callback_number = DREAMLINE_KAKAO_CALLBACK_NUMBER;
        $this->callback_key = DREAMLINE_KAKAO_CALLBACK_KEY;
    }

    public function load($test = false){
        // Include Firebase library files
        require_once APPPATH.'third_party/DreamlineKakao/RestApi.php';

        $base_url = $this->base_url;
        $auth_key = $this->auth_key;
        $id_type = $this->id_type;
        $id = $this->id;
        $pw = $this->pw;
        $callback_number = $this->callback_number;
        $callback_key = $this->callback_key;

        $RestApi = new RestApi($base_url, $auth_key, $id_type, $id, $callback_number, $callback_key, $pw, $test);

        return $RestApi;
    }

    public function getContent($RestApi, $template_code, $dto = [])
    {
        $ret = $RestApi->search($template_code);

        if($ret['response']->code !== '200'){
            return '';
        }else{
            $content = $ret['response']->data->templateContent;

            $it_TotalComma = $dto['it_ProgramMonthCount']*$dto['it_ProgramCommaPerWeek'];
            $it_Installment = ((int)$dto['it_Installment'] === 1)?'일시불':$dto['it_Installment'].'개월 할부';
            $it_PaymentAmount  = number_format($dto['it_PaymentAmount']);

            $content = str_replace('#{학생이름}', $dto['st_StudentName'], $content);
            $content = str_replace('#{과목}', $dto['st_SubjectName'], $content);
            $content = str_replace('#{주당횟수}', $dto['it_ProgramCommaPerWeek'], $content);
            $content = str_replace('#{단위시간}', $dto['it_ProgramTutoringMin'], $content);
            $content = str_replace('#{개월수}', $dto['it_ProgramMonthCount'], $content);
            $content = str_replace('#{총수업수}', $it_TotalComma, $content);
            $content = str_replace('#{금액}', $it_PaymentAmount, $content);
            $content = str_replace('#{할부기간}', $it_Installment, $content);

            switch ($template_code) {
                case 'SYSTEM_KT_01' : // 예정된 자동 연장결제
                    $dt_PaymentDate = date('Y년 m월 d일', $dto['dt_PaymentDate']);
                    $content = str_replace('#{결제예정일}', $dt_PaymentDate, $content);
                    break;
                case 'SYSTEM_KT_02' : // 자동 연장결제 실패
                    $dt_PaymentDate = date('Y년 m월 d일', $dto['dt_PaymentDate']);
                    $content = str_replace('#{결제예정일}', $dt_PaymentDate, $content);
                    $content = str_replace('#{실패사유}', $dto['st_ResultMsg'], $content);
                    break;
                case 'SYSTEM_KT_03' : // 자동 연장결제 성공
                    $dt_PaymentDate = date('Y년 m월 d일', $dto['dt_PaymentDate']);
                    $content = str_replace('#{결제일}', $dt_PaymentDate, $content);
                    break;
                case 'SYSTEM_KT_04' : // 에스코치 결제 성공
                    $dt_PaymentDate = date('Y년 m월 d일', $dto['dt_PaymentDate']);
                    $content = str_replace('#{결제일자}', $dt_PaymentDate, $content);
                    break;
                case 'SYSTEM_KT_05' : // 에스코치 정기결제 예약
                    $dt_PaymentDate = date('Y년 m월 d일', $dto['dt_PaymentDate']);
                    $content = str_replace('#{결제예약일자}', $dt_PaymentDate, $content);
                    break;
                case 'SYSTEM_KT_06' : // 에스코치 수강등록 완료
                    $dt_PaymentDate = date('Y년 m월 d일', $dto['dt_PaymentDate']);

                    $st_CourseSchedule  = "";
                    foreach ($dto['li_DesiredClassTime'] as $li_DesiredClass){
                        $st_CourseSchedule .= $li_DesiredClass['li_DesiredDow']."요일 ";
                        $st_CourseSchedule .= getTimeAppendMeridiem($li_DesiredClass['li_DesiredStartTime'])." ~ ";
                        $st_CourseSchedule .= getTimeAppendMeridiem($li_DesiredClass['li_DesiredEndTime'])."\n";
                    }
                    $content = str_replace('#{결제일자}', $dt_PaymentDate, $content);
                    $content = str_replace('#{수업가능일정}', $st_CourseSchedule, $content);
                    break;
            }

            return $content;
        }
    }
}