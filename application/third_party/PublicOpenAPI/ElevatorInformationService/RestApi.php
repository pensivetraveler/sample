<?php

/**
 * Class RestApi
 * 승강기 정보 및 검사 이력 서비스
 * https://www.data.go.kr/data/15000823/openapi.do
 */
class RestApi
{
    protected $serviceKey;
    protected $baseUrl = "http://openapi.elevator.go.kr/openapi/service";
    protected $url;
    protected $queryParams;

    function __construct($serviceKey)
    {
        $this->serviceKey = "?serviceKey={$serviceKey}";
    }

    /**
     * 승강기 목록 조회
     * 승강기 목록 조회하는 기능 제공
     * @param $sido 소재지(시/도) (필수)
     * @param $sigungu 소재시(시/군/구) (필수)
     * @param $buld_nm 건물명 (옵션)
     * @param $numOfRows 한 페이지 결과 수 (옵션)
     * @param $pageNo 페이지 번호 (옵션)
     */
    public function getElevatorList($numOfRows, $pageNo, $sido = '', $sigungu = '', $buld_nm = '')
    {
        $url = $this->base_url."/ElevatorInformationService/getElevatorList";
        $queryParams = "&numOfRows={$numOfRows}&pageNo={$pageNo}&sido={$sido}&sigungu={$sigungu}&buld_nm={$buld_nm}";

        $this->url = $url;
        $this->queryParams = $queryParams;

        $xml = $this->GET();

        return $xml;
    }

    /**
     * 승강기 상세 정보 조회
     * 승강기 상세정보 조회 하는 기능 제공
     * @param $elevator_no 승강기번호 (필수)
     */
    public function getElevatorView($elevator_no)
    {
        $url = $this->base_url."/ElevatorInformationService/getElevatorView";
        $queryParams = "&elevator_no={$elevator_no}";

        $this->url = $url;
        $this->queryParams = $queryParams;

        $xml = $this->GET();

        return $xml;
    }

    /**
     * 승강기 검사이력 조회
     * 승강기 검사 이력 조회 하는 기능 제공
     * @param $elevator_no 승강기번호 (필수)
     * @param $numOfRows 한 페이지 결과 수 (필수)
     * @param $pageNo 페이지 번호 (필수)
     */
    public function getElvtrInspctInqire($elevator_no, $numOfRows = '1', $pageNo = '1')
    {
        $url = $this->base_url."/ElevatorInformationService/getElvtrInspctInqire";
        $queryParams = "&elevator_no={$elevator_no}&numOfRows={$numOfRows}&pageNo={$pageNo}";

        $this->url = $url;
        $this->queryParams = $queryParams;

        $xml = $this->GET();

        $data = $this->parseXmlForElvtrInspctInqire($xml);

        return $data;
    }

    /**
     * 사고 및 고장 승강기 이력 정보 조회
     * 사고 및 고장 승강기 이력 조회 하는 기능 제공
     * @param $elevator_no 승강기번호 (필수)
     * @param string $flag 고장/사고 구분 (옵션)
     * @param string $area 주소1 (옵션)
     * @param string $buld_nm 건물명 (옵션)
     */
    public function getDefectElvtrInqire($elevator_no, $flag = '', $area = '', $buld_nm = '')
    {
        $url = $this->base_url."/DefectElvtrInqireService/getElvtrInspctInqire";
        $queryParams = "&elevator_no={$elevator_no}&flag={$flag}&area={$area}&buld_nm={$buld_nm}";

        $this->url = $url;
        $this->queryParams = $queryParams;

        $xml = $this->GET();

        return $xml;
    }

    /**
     * 승강기 유지관리 업체목록 조회
     * 승강기 유지관리 업체 목록 조회하는 기능 제공
     * @param $trnm 업체명 (옵션)
     * @param string $area (옵션)
     */
    public function getElvtrMntmingList($trnm, $area = '')
    {
        $url = $this->base_url."/ElvtrMntmingListService/getElvtrMntmingList";
        $queryParams = "&trnm={$trnm}&area={$area}";

        $this->url = $url;
        $this->queryParams = $queryParams;

        $xml = $this->GET();

        return $xml;
    }

    protected function getTimestamp()
    {
        return round(microtime(true) * 1000);
    }

    protected function getHeader($method, $uri)
    {
        $timestamp = $this->getTimestamp();
        $header = array(
            'Content-Type: application/json; charset=UTF-8',
            'X-Timestamp: ' . $timestamp,
            'X-API-KEY: ' . $this->apiKey,
            'X-Customer: ' . $this->customerId,
            'X-Signature: ' . $this->generateSignature($timestamp, $method, $uri),
        );
        return $header;
    }

    protected function build_http_query($query)
    {
        if (!empty ($query)) {
            $query_array = array();
            foreach ($query as $key => $key_value) {
                $query_array [] = urlencode($key) . '=' . urlencode($key_value);
            }

            return implode('&', $query_array);
        } else {
            return '';
        }
    }

    protected function GET()
    {
        $uri = $this->url.$this->serviceKey;
        $query = $this->queryParams;

        $ch = curl_init();
        if (!$ch) {
            die ("Couldn't initialize a cURL handle");
        }
        curl_setopt($ch, CURLOPT_URL, $this->baseUrl.$uri.$query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $output = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        $xml = simplexml_load_string($output);

        return $xml;
    }

    protected function parseXmlForElvtrInspctInqire($xml)
    {
        $arr = XML2Array($xml);

        $data = new stdClass();
        $data->applcBeDt = '-';
        $data->applcEnDt = '-';
        $data->inspctDt = '-';
        $data->inspctInsttNm = '-';
        $data->inspctKind = '-';
        $data->psexamYn = '-';
        $data->recptnNo = '-';

        if(count($arr['body']['items']['item']) > 0){
            if(isset($arr['body']['items']['item']['psexamYn']) && !empty($arr['body']['items']['item']['psexamYn'])){
                $item = $arr['body']['items']['item'];
            }else{
                $item = $arr['body']['items']['item'][0];
            }

            $data->applcBeDt = $item['applcBeDt'];                //운행시작일
            $data->applcEnDt = $item['applcEnDt'];                //운행종료일
            $data->inspctDt = $item['inspctDt'];                  //검사일자
            $data->inspctInsttNm = $item['inspctInsttNm'];        //검사기관
            $data->inspctKind = $item['inspctKind'];              //검사종류
            $data->psexamYn = $item['psexamYn'];                  //합격유무
            $data->recptnNo = $item['recptnNo'];                  //접수번호
        }

        return $data;
    }
}

?>
