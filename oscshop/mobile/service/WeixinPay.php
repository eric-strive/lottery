<?php

namespace osc\mobile\service;

use osc\admin\model\LuckRecord;
use osc\admin\model\Member;
use osc\admin\model\PayOrder;
use think\Db;
use think\Exception;
use wechat\Curl;

//微信支付处理
class WeixinPay
{
    //微信支付 package
    public static function getBizPackage($data)
    {
        $wx = wechat();
        // 订单总额
        $totalFee = ($data['pay_total']) * 100;
        // 随机字符串
        $nonceStr = $wx->generateNonceStr();

        $config = payment_config('weixin');

        // 时间戳
        $timeStamp = strval(time());

        $pack = [
            'appid'            => $config['appid'],
            'body'             => $data['subject'],
            'mch_id'           => $config['weixin_partner'],
            'nonce_str'        => $nonceStr,
            'notify_url'       => request()->domain() . url('lottery_payment/jsskd_notify'),
            'spbill_create_ip' => get_client_ip(),
            'openid'           => $wx->getOpenId(),
            // 外部订单号
            'out_trade_no'     => $data['pay_order_no'],
            'timeStamp'        => $timeStamp,
            'total_fee'        => $totalFee,
            'attach'           => isset($data['attach']) ? $data['attach'] : 'other',
            'trade_type'       => 'JSAPI',
        ];

        $pack['sign'] = $wx->paySign($pack);

        $xml = $wx->toXML($pack);

        $ret = Curl::post('https://api.mch.weixin.qq.com/pay/unifiedorder', $xml);

        $postObj = json_decode(json_encode(simplexml_load_string($ret, 'SimpleXMLElement', LIBXML_NOCDATA)));

        if (empty($postObj->prepay_id) || $postObj->return_code == "FAIL") {
            throw new Exception('创建支付失败');
        } else {
            $packJs = [
                'appId'     => $config['appid'],
                'timeStamp' => $timeStamp,
                'nonceStr'  => $nonceStr,
                'package'   => "prepay_id=" . $postObj->prepay_id,
                'signType'  => 'MD5',
            ];

            $JsSign = $wx->paySign($packJs);

            $p['timestamp'] = $timeStamp;
            $p['nonceStr']  = $nonceStr;
            $p['package']   = "prepay_id=" . $postObj->prepay_id;
            $p['signType']  = 'MD5';
            $p['paySign']   = $JsSign;

            return ['ret_code' => 0, 'bizPackage' => $p, 'out_trade_no' => $data['pay_order_no']];
        }
    }

    /**
     * 余额支付
     *
     * @param $data
     *
     * @throws Exception
     */
    public static function balancePay($data, $homeId, $uid)
    {
        $memberInfo = Member::getMemberInfo($uid, true);
        $pay_total  = $data['pay_total'];
        $orderNo    = $data['pay_order_no'];
        if ($pay_total > $memberInfo['balance']) {
            throw new Exception('余额不足!请充值。');
        }
        $s = Member::addBalance($uid, $pay_total, Member::REDUCE);
        if ($s === false) {
            throw new Exception('订单出错');
        }
        Member::addBalanceRecord([
            'uid'         => $uid,
            'amount'      => $pay_total,
            'description' => '夺宝消费',
            'prefix'      => Member::REDUCE,
            'create_time' => time(),
            'home_id'     => $homeId,
            'type'        => 1,
            'order_no'    => $orderNo,
        ]);
        $orderInfo = PayOrder::orderInfo($orderNo);
        OrderProcess::addNumber($orderInfo, $homeId);
        PayOrder::editStatus($orderNo, PayOrder::STATUS_SUCCESS_PAY);
    }

    /**
     * 余额支付幸运购
     *
     * @param $orderNo
     * @param $pay_total
     * @param $uid
     *
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function luckBalancePay($orderNo, $pay_total, $uid)
    {
        Member::giveBalanceLuck($uid, $pay_total, 5, Member::REDUCE);
        LuckRecord::setStatus($orderNo);
        PayOrder::editStatus($orderNo, PayOrder::STATUS_SUCCESS_PAY);
    }

    /**
     * 购买商品
     */
    public static function goodsBug()
    {
        $gid       = input('gid', 0);
        $attach    = input('attach', 0);
        $uid       = user('uid');
        $goodsInfo = osc_goods()->get_goods_info($gid);
        $userInfo  = Db::name('member')->where('uid', $uid)->find();
        $price     = $goodsInfo['goods']['price'];
        $bugInfo   = [
            'gid'       => $gid,
            'g_pic'     => $goodsInfo['goods']['image'],
            'g_name'    => $goodsInfo['goods']['name'],
            'price'     => $price,
            'nickname'  => $userInfo['nickname'],
            'uid'       => $uid,
            'pay_type'  => 1,
            'status'    => 1,
            'create_at' => date("Y-m-d H:i:s"),
        ];
        Db::name('buy_record')->insert($bugInfo);
        Member::giveBalanceLuck($uid, $price, $attach, Member::REDUCE);
    }
}

?>