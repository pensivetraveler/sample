<?php

class Inicis_bill_lib
{
    private string $mid;
    private string $signkey;
    private string $INIAPI_key;
    private string $INIAPI_iv;
    private string $INILite_key;
    private string $INIMobile_Hashkey;

    public function __construct()
    {
        log_message('Debug', 'Inicis_lib class is loaded.');

        require_once APPPATH . 'third_party/InicisBill/CreateIdModule.php';
        require_once APPPATH . 'third_party/InicisBill/HttpClient.php';
        require_once APPPATH . 'third_party/InicisBill/INIStdPayUtil.php';
    }

    public function load($test = false)
    {
        // 정기결제 (https://manual.inicis.com/pay/bill_pc.html)
        if($test){
            $this->mid = "INIBillTst";
            $this->signkey = "SU5JTElURV9UUklQTEVERVNfS0VZU1RS";
            $this->INIAPI_key = "rKnPljRn5m6J9Mzz";
            $this->INIAPI_iv = "W2KLNKra6Wxc1P==";
            $this->INILite_key = 'b09LVzhuTGZVaEY1WmJoQnZzdXpRdz09';
            $this->INIMobile_Hashkey = 'b09LVzhuTGZVaEY1WmJoQnZzdXpRdz09';
        }else{
            $this->mid = INICIS_BILL_MID;
            $this->signkey = INICIS_BILL_SIGNKEY;
            $this->INIAPI_key = INICIS_BILL_INIAPI_KEY;
            $this->INIAPI_iv = INICIS_BILL_INIAPI_IV;
            $this->INILite_key = INICIS_BILL_INILITE_KEY;
            $this->INIMobile_Hashkey = INICIS_BILL_MOBILE_HASH_KEY;
        }
    }

    public function getInicisData($price)
    {
        $SignatureUtil = new INIStdPayUtil();

        $timestamp = $SignatureUtil->getTimestamp();   // util에 의해서 자동생성
        $orderNumber = $this->getOrderNumber($timestamp);
        $price = 10000;
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

    public function billKeyRequest()
    {
        $st_ErrorCode  = '';
        $st_ErrorMessage = '';
        $li_ResultData = [];
        $it_RequestSuccess = false;

        /**
         * STEP 2. 인증결과 수신
         */
        $resultCode = $_POST['resultCode'];
        $resultMsg = $_POST['resultMsg'];

        $SignatureUtil = new INIStdPayUtil();

        try {
            // 인증이 성공일 경우만
            if (strcmp("0000", $resultCode) == 0) {
                // 1.전문 필드 값 설정
                $mid        = $_REQUEST["mid"];
                $timestamp  = $SignatureUtil->getTimestamp();
                $charset    = "UTF-8";
                $format     = "JSON";
                $authToken  = $_REQUEST["authToken"];
                $authUrl    = $_REQUEST["authUrl"];
                $netCancel  = $_REQUEST["netCancelUrl"];

                // 2.signature 생성
                $signParam["authToken"] = $authToken;   // 필수
                $signParam["timestamp"] = $timestamp;   // 필수
                // signature 데이터 생성 (모듈에서 자동으로 signParam을 알파벳 순으로 정렬후 NVP 방식으로 나열해 hash)
                $signature = $SignatureUtil->makeSignature($signParam);

                // 3.API 요청 전문 생성
                $authMap["mid"]        = $mid;       // 필수
                $authMap["authToken"]  = $authToken; // 필수
                $authMap["signature"]  = $signature; // 필수
                $authMap["timestamp"]  = $timestamp; // 필수
                $authMap["charset"]    = $charset;   // default=UTF-8
                $authMap["format"]     = $format;    // default=XML

                try {
                    $httpUtil = new HttpClient();

                    // 4.API 통신 시작
                    $authResultString = "";
                    if ($httpUtil->processHTTP($authUrl, $authMap)) {
                        $authResultString = $httpUtil->body;
                    } else {
                        throw new Exception("Http Connect Error | {$httpUtil->errormsg}");
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
                        $resultCode = @(in_array($resultMap["resultCode"], $resultMap) ? $resultMap["resultCode"] : "null");

                        if (strcmp($secureSignature, $resultMap["authSignature"]) != 0) {
                            //결제보안키가 다른 경우.
                            $resultMsg = '데이터 위변조 체크 실패';
                        } else {
                            $resultMsg = @(in_array($resultMap["resultMsg"], $resultMap) ? $resultMap["resultMsg"] : "null");
                        }
                    }else{
                        // 요청 성공
                        $it_RequestSuccess = true;
                    }

                    $li_ResultData = $resultMap;
                }catch (Exception $e) {
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
            }else{
                // 인증 실패시
                $resultMsg = ($resultMsg)?:'인증실패';
            }
        }catch (Exception $e) {
            $st_ErrorCode = $e->getCode();
            $st_ErrorMessage = $e->getMessage();
        }

        return [
            'resultCode' => $resultCode,
            'resultMsg' => $resultMsg,
            'errorCode' => $st_ErrorCode,
            'errorMessage' => $st_ErrorMessage,
            'requestSuccess' => $it_RequestSuccess,
            'resultData' => $li_ResultData,
        ];
    }

    public function billOrderRequest($dto)
    {
        header("Content-Type: text/html; charset=utf-8");

        // hash 암호화
        $dto['mid'] = $this->mid;
        $dto['hashData'] = hash("sha512",$this->INIAPI_key.$dto['type'].$dto['paymethod'].$dto['timestamp'].$dto['clientIp'].$this->mid.$dto['moid'].$dto['price'].$dto['billKey']);

        $url = "https://iniapi.inicis.com/api/v1/billing";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dto));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_POST, 1);

        $response = curl_exec($ch);
        curl_close($ch);

        $resultData = (array)json_decode($response);

        return [
            'requestSuccess' => $this->validateResultCode($resultData['resultCode']),
            'resultData' => $resultData,
        ];
    }

    protected function getOrderNumber($timestamp)
    {
        // 가맹점 주문번호(가맹점에서 직접 설정)
        return $this->mid . "_" . $timestamp;
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