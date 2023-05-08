<?php

class Inicis_pay_lib
{
    private string $mid;
    private string $signkey;
    private string $INIAPI_key;
    private string $INIAPI_iv;
    private string $INILite_key;
    private string $Mobile_Hashkey;

    public function __construct()
    {
        log_message('Debug', 'Inicis_lib class is loaded.');

        require_once APPPATH . 'third_party/InicisPay/CreateIdModule.php';
        require_once APPPATH . 'third_party/InicisPay/HttpClient.php';
        require_once APPPATH . 'third_party/InicisPay/INIStdPayUtil.php';
    }

    public function load($test = false)
    {
        // 일반결제 (https://manual.inicis.com/pay/stdpay_pc.html)
        if($test){
            $this->signkey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS";
            $this->INIAPI_key = "ItEQKi3rY7uvDS8l";
            $this->INIAPI_iv = "HYb3yQ4f65QL89==";
            $this->Mobile_Hashkey = '3CB8183A4BE283555ACC8363C0360223';
        }else{
            $this->mid = INICIS_PAY_MID;
            $this->signkey = INICIS_PAY_WEB;
            $this->INIAPI_key = "";
            $this->INIAPI_iv = "";
            $this->Mobile_Hashkey = INICIS_SIGNKEY_MOB;
        }
    }

    public function getInicisData($price)
    {
        $SignatureUtil = new INIStdPayUtil();

        $timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성
        $orderNumber = $this->getOrderNumber($timestamp);
        $price = 1000;
        $cardNoInterestQuota = "11-2:3:,34-5:12,14-6:12:24,12-12:36,06-9:12,01-3:4";  // 카드 무이자 여부 설정(가맹점에서 직접 설정)
        $cardQuotaBase = "2:3:4:5:6:11:12:24:36";  // 가맹점에서 사용할 할부 개월수 설정
        $mKey = $SignatureUtil->makeHash($this->signkey, "sha256");
        $params = array(
            "oid" => $orderNumber,
            "price" => $price,
            "timestamp" => $timestamp
        );
        $sign = $SignatureUtil->makeSignature($params, "sha256");

//        $siteDomain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http")."://{$_SERVER['HTTP_HOST']}/payment";
        $siteDomain = _HTTP."{$_SERVER['HTTP_HOST']}/payment";

        return array(
            'mid' => $this->mid,
            'signKey' => $this->signkey,
            'timestamp' => $timestamp,
            'orderNumber' => $orderNumber,
            'price' => $price, // 상품가격(특수기호 제외, 가맹점에서 직접 설정)
            'cardNoInterestQuota' => $cardNoInterestQuota,
            'cardQuotaBase' => $cardQuotaBase,
            'mKey' => $mKey,
            'params' => $params,
            'sign' => $sign,
            'siteDomain' => $siteDomain,
        );
    }

    public function orderProcWeb()
    {
        $SignatureUtil = new INIStdPayUtil();

        $resultCode = $_POST['resultCode'];
        $resultMsg = $_POST['resultMsg'];
        $st_ErrorCode  = '';
        $st_ErrorMessage = '';
        $li_ResultData = [];
        $it_RequestSuccess = 0;

        try {
            // 인증이 성공일 경우만
            if (strcmp("0000", $resultCode) == 0) {
                // 1.전문 필드 값 설정
                $mid = $_POST['mid'];                                   // 가맹점 ID 수신 받은 데이터로 설정
                $signKey = $this->signkey;                           // 가맹점에 제공된 키(이니라이트키) (가맹점 수정후 고정) !!!절대!! 전문 데이터로 설정금지
                $timestamp = $SignatureUtil->getTimestamp();            // util에 의해서 자동생성
                $charset = "UTF-8";                                     // 리턴형식[UTF-8,EUC-KR](가맹점 수정후 고정)
                $format = "JSON";                                       // 리턴형식[XML,JSON,NVP](가맹점 수정후 고정)
                $authToken = $_POST["authToken"];                       // 취소 요청 tid에 따라서 유동적(가맹점 수정후 고정)
                $authUrl = $_POST["authUrl"];                           // 승인요청 API url(수신 받은 값으로 설정, 임의 세팅 금지)
                $netCancel = $_POST["netCancelUrl"];                    // 망취소 API url(수신 받은f값으로 설정, 임의 세팅 금지)
                $mKey = hash("sha256", $signKey);                  // 가맹점 확인을 위한 signKey를 해시값으로 변경 (SHA-256방식 사용)

                // 2.signature 생성
                $signParam["authToken"] = $authToken;                   // 필수
                $signParam["timestamp"] = $timestamp;                   // 필수
                // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
                $signature = $SignatureUtil->makeSignature($signParam);

                // 3.API 요청 전문 생성
                $authMap = array(
                    "mid" => $mid,                                      // 필수
                    "authToken" => $authToken,                          // 필수
                    "signature" => $signature,                          // 필수
                    "timestamp" => $timestamp,                          // 필수
                    "charset" => $charset,                              // default=UTF-8
                    "format" => $format,                                // default=XML
                );

                try {
                    $httpUtil = new HttpClient();

                    // 4.API 통신 시작
                    $authResultString = "";
                    if ($httpUtil->processHTTP($authUrl, $authMap)) {
                        $authResultString .= $httpUtil->body;
                    } else {
                        throw new Exception("Http Connect Error");
                    }

                    //5.API 통신결과 처리(***가맹점 개발수정***)
                    $resultMap = json_decode($authResultString, true);

                    //6. 결제 보안 : signature 데이터 생성
                    $secureMap["mid"] = $mid;                           //mid
                    $secureMap["tstamp"] = $timestamp;                  //timestemp
                    $secureMap["MOID"] = $resultMap["MOID"];            //MOID
                    $secureMap["TotPrice"] = $resultMap["TotPrice"];    //TotPrice
                    $secureSignature = $SignatureUtil->makeSignatureAuth($secureMap);

                    if(!$this->validateResultCode($resultMap["resultCode"]) || strcmp($secureSignature, $resultMap["authSignature"]) !== 0) {
                        $resultCode = @(in_array($resultMap["resultCode"] , $resultMap) ? $resultMap["resultCode"] : "null" );

                        if (strcmp($secureSignature, $resultMap["authSignature"]) != 0) {
                            //결제보안키가 다른 경우.
                            $resultMsg = '데이터 위변조 체크 실패';
                        }else{
                            $resultMsg = @(in_array($resultMap["resultMsg"] , $resultMap) ? $resultMap["resultMsg"] : "null" );
                        }
                    }else{
                        // 요청 성공
                        $it_RequestSuccess = 1;
                    }

                    $li_ResultData = $resultMap;
                } catch (Exception $e) {
                    // 망취소 API
                    $netcancelResultString = ""; // 망취소 요청 API url(고정, 임의 세팅 금지)
                    if ($httpUtil->processHTTP($netCancel, $authMap)) {
                        $netcancelResultString = $httpUtil->body;
                    } else {
                        throw new Exception("Http Connect Error");
                    }

                    $st_ErrorCode = $e->getCode();
                    $st_ErrorMessage = $e->getMessage().'|'.$netcancelResultString;
                }
            } else {
                // 인증 실패시
                $resultMsg = ($resultMsg)?:'인증실패';
            }
        } catch (Exception $e) {
            $st_ErrorCode = $e->getCode();
            $st_ErrorMessage = $e->getMessage();
        }

        return [
            'st_ResultCode' => $resultCode,
            'st_ResultMsg' => $resultMsg,
            'st_ErrorCode' => $st_ErrorCode,
            'st_ErrorMessage' => $st_ErrorMessage,
            'it_RequestSuccess' => $it_RequestSuccess,
            'li_ResultData' => $li_ResultData,
        ];
    }

    public function orderProcMob()
    {
        $st_ErrorCode  = '';
        $st_ErrorMessage = '';
        $li_ResultData = [];
        $it_RequestSuccess = 0;

        /**
         * STEP 2. 인증결과 수신
         */
        // 결과 코드
        $resultCode = $_POST['P_STATUS'];
        // 결과 메세지
        $resultMsg = $_POST['P_RMESG1'];
        // 인증거래번호
        $p_tid = $_POST['P_TID'];
        // 거래 금액
        $p_amt = $_POST['P_AMT'];
        // 승인요청 URL
        $p_req_url = $_POST['P_REQ_URL'];

        /**
         * STEP 2. 승인요청/응답
         */
        if ($resultCode === '00') {
            $id_merchant = substr($p_tid, '10', '10');     // P_TID 내 MID 구분
            $data = array(
                // P_MID
                'P_MID' => $id_merchant,
                // P_TID
                'P_TID' => $p_tid
            );

            // curl 통신 시작
            $ch = curl_init();                                                             //curl 초기화
            curl_setopt($ch, CURLOPT_URL, $p_req_url);      //URL 지정하기
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);     //connection timeout 10초
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);           //원격 서버의 인증서가 유효한지 검사 안함
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //POST 로 $data 를 보냄
            curl_setopt($ch, CURLOPT_POST, 1);                                         //true시 post 전송

            $response = curl_exec($ch);
            curl_close($ch);

            // 승인결과 수신
            $resultMap = $this->getOrderProcMobileResultMap($response);

            // 사용한도 초과 result code인 (00GT, 0016, 0057 대비)
            if(!$this->validateResultCode($resultMap["P_STATUS"])){
                // 요청 성공
                $it_RequestSuccess = 1;
                $li_ResultData = $resultMap;
            }

            $resultCode = $resultMap['P_STATUS'];
            $resultMsg = $resultMap['P_RMESG1'];
        }

        return [
            'st_ResultCode' => $resultCode,
            'st_ResultMsg' => $resultMsg,
            'st_ErrorCode' => $st_ErrorCode,
            'st_ErrorMessage' => $st_ErrorMessage,
            'it_RequestSuccess' => $it_RequestSuccess,
            'li_ResultData' => $li_ResultData,
        ];
    }

    protected function getOrderNumber($timestamp)
    {
        // 가맹점 주문번호(가맹점에서 직접 설정)
        return $this->mid . "_" . $timestamp;
    }

    protected function getOrderProcMobileResultMap($response)
    {
        $result = array();

        $responseArr = explode("&", $response);

        foreach ($responseArr as $data){
            $dataArr = explode('=', $data);
            $result[$dataArr[0]] = $dataArr[1];
        }

        return $result;
    }

    protected function validateResultCode($resultCode)
    {
        $validate = false;

        if(strcmp("00", $resultCode) == 0 || strcmp("0000", $resultCode) == 0){
            if($resultCode === '00' || $resultCode === '0000'){
                $validate = true;
            }
        }

        return $validate;
    }
}