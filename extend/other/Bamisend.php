<?php

namespace other;
class Bamisend
{
    //CURL请求的函数http_request()
    public function http($url, $params = array(), $method = 'get', $ssl = false)
    {
        $opts = array(
            CURLOPT_TIMEOUT => 30,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
        );
        /* 根据请求类型设置特定参数 */
        switch (strtoupper($method)) {
            case 'GET':
                $getQuerys = !empty($params) ? '?' . urldecode(http_build_query($params)) : '';
                $opts[CURLOPT_URL] = $url . $getQuerys;
                break;
            case 'POST':
                $opts[CURLOPT_URL] = $url;
                $opts[CURLOPT_POST] = 1;
                $opts[CURLOPT_POSTFIELDS] = $params;
                break;
        }
        /* 初始化并执行curl请求 */
        $ch = curl_init();
        curl_setopt_array($ch, $opts);
        $data = curl_exec($ch);
        $err = curl_errno($ch);
        $errmsg = curl_error($ch);
        curl_close($ch);
        if ($err > 0) {
            dump('CURL:' . $errmsg);
            //exit;
            self::errors('CURL:' . $errmsg);
            return false;
        } else {
            return $data;
        }
    }

    public function sendmobile($phonelist, $content)
    {
        $url = 'http://sms.bamikeji.com:8890/mtPort/mt/normal/send';
        $params = array(
            'uid' => 1674,
            'passwd' => '1b2da3960dcf96ee8b901546c3d92a85',
            'phonelist' => $phonelist,
            'content' => $content,
        );
        $return = $this->http($url, $params);
        return json_decode($return, true);
    }

    public function sendsms($mobile, $content)
    {
        $url = 'http://47.110.197.197:8081/api/sms/send';
        $userid = '350114';
        $ts = time();
        $apikey = 'fd81e037a43d47a8858f78ab0797189c';
        $sign = md5($userid . $ts . $apikey);
        $params = array(
            'userid' => $userid,
            'ts' => $ts,
            'sign' => $sign,
            'mobile' => $mobile,
            'msgcontent' => $content,
        );
        $return = $this->http($url, $params, 'post');
        return json_decode($return, true);
    }

    public function checkphone($mobile)
    {
        $url = 'http://apis.haoservice.com/thirdnode/phonenotest/';
        $apikey = '';
        $params = array(
            'key' => $apikey,
            'mobiles' => $mobile,
        );
        $return = $this->http($url, $params, 'post');
        return json_decode($return, true);
    }

}