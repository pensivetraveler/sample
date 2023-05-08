<?php

class RestApi
{
    protected $apiKey;
    protected $baseUrl;

    function __construct($baseUrl, $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->baseUrl = $baseUrl;
    }

    protected function getHeader()
    {
        $apiKey = $this->apiKey;

		$header = array(
			'Authorization:key ='.$apiKey,
			'Content-Type: application/json'
		);

        return $header;
    }

    protected function get_name($field)
    {
        $arr = explode(":", $field);
        $name = str_replace("\"", "", trim($arr[0]));
        if(substr($name, 0, 1) == "{"){
            $name = substr($name, 1);
        }
        elseif(substr($name, 0, 2) == "[{"){
            $name = substr($name, 2);
        }

        return $name;
    }

    protected function get_value($field)
    {
        // print_r($field);
        $arr = explode(":", $field, 2);

        $name = str_replace("\"", "", trim($arr[0]));
        if($name == 'results'){
            $arr_2 = explode(":", $arr[1], 2);

            $name = str_replace("\"", "", trim($arr_2[0]));
            $name = substr($name, 2);

            $value = str_replace("\"", "", trim($arr_2[1]));
            $value = substr($value, 0, -3);

            $data = array(
                $name => $value
            );
        }else{
            $data = trim($arr[1]);
        }

        return $data;
    }

    protected function parseResponse($response)
    {
        $data = array();

        if (!empty ($response)) {
            $list = explode("\r\n\r\n", $response);

            foreach($list as $key => $element){
                $fields = explode(",", $element);

                $field_arr = array();
                foreach($fields as $field) {
                    $name = $this->get_name($field);
                    $value = $this->get_value($field);

                    $field_arr[$key][$name] = $value;
                }
                $data[] = $field_arr;
            }
        }

        return $data;
    }

    public function send($fields)
    {
        $baseUrl = $this->baseUrl;
        $header = $this->getHeader();

        $fieldsJson = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $baseUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fieldsJson);

        $output = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if (empty ($code)) {
            $rtn_status = '';
        } else {
            $rtn_status = $code;
        }

        $rtn_response = $this->parseResponse($output);

        $rtn_error = '';
        if (!empty ($error)) {
            $rtn_error = $error;
        }

        $rtn = array(
            'status' => $rtn_status
            , 'response' => $rtn_response
            , 'error' => $rtn_error
            , 'data' => $fields
        );

        return $rtn;
    }

    public function dataPayload($tokens, $data, $device)
    {
        // notification payload 정의
        $notification = $data;
        $notification->sound = "default";
        $notification->vibrate =  '[300, 100, 400]';

        // data payload로 보내서 앱이 백그라운드이든 포그라운드이든 무조건 알림이 떠도록 하자.
        switch ($device) {
            case 'web' :
                $fields = array(
                    // 대상
                    'registration_ids' => $tokens,
                    // 옵션
                    'collapse_key' => 'Updates Available',
                    'priority' => 'high',
                    // 페이로드
                    'data' => $data,
                    'notification' => $notification
                );
                break;
            case 'android' :
                $fields = array(
                    // 대상
                    'registration_ids' => $tokens,
                    // 옵션
                    'collapse_key' => 'Updates Available',
                    'priority' => 'high',
                    // 페이로드
                    'data' => $data
                );
                break;
            case 'iphone' :
                $fields = array(
                    // 대상
                    'registration_ids' => $tokens,
                    // 옵션
                    'collapse_key' => 'Updates Available',
                    'content_available' => (bool)true,
                    'mutable_content' => (bool)true,
                    'priority' => 'high',
                    // 페이로드
                    'data' => $data,
                    'notification' => $notification
                );
                break;
        }

        return $fields;
    }

    public function sendForTest($token, $title, $body, $image, $tag, $device, $lang = 'ko')
    {
        $tokens = array();
        $tokens[] = $token;

        $data = new StdClass();
        $data->title = $title;
        $data->body = $body;
        $data->icon = site_url().'/upload/landing/logo.png';
        $data->image = $image;

        if($device == 'web'){
            $data->tag = '/product/?p='.$tag.'&l='.$lang;
        }
        else if($device == 'iphone' || $device == 'android'){
            if($lang == 'zh'){
                /* 200626 수정 - 중국 쇼핑몰에 대해서, 주소 변경 */
                $data->tag = '/shop/item.php?it_id='.$tag;
            }else{
                $data->tag = '/product/detail.html?product_no='.$tag;
            }
        }

        $fields = $this->dataPayload($tokens, $data, $device);

        $rtn = $this->send($fields);

        return $rtn;
    }

    public function sendPeriodically($tokens, $title, $body, $image, $tag, $device, $lang = 'ko')
    {
        $rtn = array();

        $data = new StdClass();
        $data->title = $title;
        $data->body = $body;
        $data->icon = site_url().'/upload/landing/logo.png';
        $data->image = $image;

        if($device == 'web'){
            $data->tag = '/product/?p='.$tag.'&l='.$lang;
        }
        else if($device == 'iphone' || $device == 'android'){
            if($lang == 'zh'){
                /* 200626 수정 - 중국 쇼핑몰에 대해서, 주소 변경 */
                $data->tag = '/shop/item.php?it_id='.$tag;
            }else{
                $data->tag = '/product/detail.html?product_no='.$tag;
            }
        }

        $fields = $this->dataPayload($tokens, $data, $device);

        $rtn[] = $this->send($fields);

        return $rtn;
    }
}

?>
