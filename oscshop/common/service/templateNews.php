<?php

namespace osc\common\service;

class templateNews
{
    public $appid;

    public $appsecret;

    public function __construct($appid = null, $appsecret = null)
    {

        $this->appid     = $appid;
        $this->appsecret = $appsecret;
    }

    /**
     * @param      $tempKey
     * @param      $dataArr
     * @param      $token
     * @param null $color
     *
     * @return bool|string
     */
    public function sendTempMsg($tempKey, $dataArr, $token, $color = null)
    {
        $requestUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $token;
        $data       = $this->getData($tempKey, $dataArr);
        $tempdata   = $this->templates($tempKey);
        $color      = $color ? $color : $tempdata['color'];
        $sendData   = '{"touser":"' . $dataArr['openid'] . '","template_id":"' . $tempdata["template_id"] .
            '","url":"' . $dataArr["href"] . '","topcolor":"' . $color . '","data":' . $data . '}';

        return $sendData;

//        return $this->https_request($requestUrl, $sendData);
    }

    public function getData($key, $dataArr)
    {
        $tempsArr = $this->templates($key);
        $data     = $tempsArr['vars'];
        $color    = $tempsArr['color'];
        $data     = array_flip($data);
        $jsonData = '';
        foreach ($dataArr as $k => $v) {
            if (in_array($k, array_flip($data))) {
                $jsonData .= '"' . $k . '":{"value":"' . $v . '","color":"' . $color . '"},';
            }
        }

        $jsonData = rtrim($jsonData, ',');

        return "{" . $jsonData . "}";
    }

    public function templates($tid)
    {
        $attr = [
            'OPENTM207225527' => [
                'name'        => '操作确认申请通知',
                'template_id' => 'x5uuOMd8pK7PKfJJE0KRA3GzWTKbfQ_V_qX9oVBSb4Y',
                'vars'        => ['first', 'keyword1', 'keyword2', 'keyword3', 'remark'],
                'color'       => '#173177',
            ],
            'OPENTM201743389' => [
                'name'        => '新订单提醒通知',
                'template_id' => 'IPsu8rn7VtUGpi94LsIXdiiCoYIfQ4ymYocWxkFoq04',
                'vars'        => ['first', 'keyword1', 'keyword2', 'keyword3', 'keyword4', 'keyword5', 'remark'],
                'color'       => '#173177',
            ],
            'OPENTM400504461' => [
                'name'  => '佣金提醒',
                'vars'  => ['first', 'keyword1', 'keyword2', 'remark'],
                'color' => '#173177',
            ],
            'OPENTM407453584' => [
                'name'  => '帐号绑定提醒',
                'vars'  => ['first', 'keyword1', 'keyword2', 'remark'],
                'color' => '#173177',
            ],
        ];

        return $attr[$tid];
    }

    //CURL请求的函数http_request()
    function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);

        return $output;
    }
}
