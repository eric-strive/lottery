<?php
/**
 * oscshop2 B2C电子商务系统
 *
 * ==========================================================================
 * @link      http://www.oscshop.cn/
 * @copyright Copyright (c) 2015-2016 oscshop.cn.
 * @license   http://www.oscshop.cn/license.html License
 * ==========================================================================
 *
 * @author    李梓钿
 *
 */

namespace osc\mobile\controller;

use osc\admin\model\duobaoRecord;
use osc\admin\model\PayOrder;
use osc\common\controller\Base;
use osc\admin\model\Home;
use osc\mobile\service\OrderProcess;
use osc\mobile\service\WeixinPay;
use \think\Db;
use think\Exception;
use think\exception\ErrorException;
use wechat\Curl;

class LotteryPayment extends Base
{

    //积分兑换
    public function points_pay()
    {
        if (request()->isPost()) {

            $result = $this->validate_pay('points');

            if (isset($result['error'])) {
                return $result;
            }

            $cart = osc_cart();
            //兑换所需积分
            $pay_points = $cart->get_pay_points($result['uid'], 'points');

            if (user('points') < $pay_points) {
                return ['error' => '积分不足，剩余积分' . user('points')];
            }

            //需要配送的,积分兑换都不需要运费
            if ($result['shipping']) {
                $order['shipping_method'] = config('default_transport_id');
            } else {
                $order['shipping_method'] = '';
            }

            $order['shipping_address_id'] = input('post.address_id');

            $order['payment_method'] = 'points';
            $order['weight'] = 0;
            $order['shipping_city_id'] = $result['city_id'];
            $order['comment'] = input('post.comment');
            $order['uid'] = $result['uid'];
            $order['type'] = 'points';

            //写入订单
            $_order = osc_order()->add_order('points', $order);
            //积分
            Db::name('member')->where('uid', $result['uid'])->setDec('points', $pay_points);    //剩余
            Db::name('member')->where('uid', $result['uid'])->setInc('cash_points', $pay_points);    //已经兑换
            //写入积分记录
            Db::name('points')->insert([
                'uid' => $result['uid'],
                'order_id' => $_order['order_id'],
                'order_num_alias' => $_order['pay_order_no'],
                'points' => $pay_points,
                'description' => '积分兑换',
                'prefix' => 2,
                'creat_time' => time(),
                'type' => 1
            ]);
            //清空购物车
            Db::name('cart')->where(array('uid' => $result['uid'], 'type' => 'points'))->delete();
            //清空购物车
            osc_order()->update_order($_order['order_id']);

            return ['success' => 1];
        }
    }

    //清空购物车
    private function clear_cart($uid, $type = 'money')
    {
        Db::name('cart')->where(array('uid' => $uid, 'type' => $type))->delete();
        session('mobile_total', null);
    }

    //数据验证
    private function validate_pay($type = 'money')
    {

        $uid = user('uid');

        if (!$uid) {
            return ['error' => '请先登录'];
        }

        $cart = osc_cart();

        if (!$cart->count_cart_total($uid, $type)) {
            return ['error' => '您的购物车没有商品'];
        }

        $city_id = input('post.city_id');

        $shipping = $cart->has_shipping(user('uid'), $type);
        //配送验证
        if (isset($shipping['error'])) {
            return ['error' => $shipping['error']];
        }

        //需要配送的
        if ($shipping) {
            if ($city_id == '') {
                return ['error' => '请选择收货地址'];
            }
        }

        // 验证商品数量
        $cart_list = Db::name('cart')->where('uid', $uid)->select();

        foreach ($cart_list as $k => $v) {

            $param['option'] = json_decode($v['goods_option'], true);
            $param['goods_id'] = $v['goods_id'];
            $param['quantity'] = $v['quantity'];

            //判断商品是否存在，验证最小起订量
            if ($error = $cart->check_minimum($param)) {
                return $error;
            }

            //验证商品数量和必选项
            if ($error = $cart->check_quantity($param)) {
                return $error;
            }

        }
        return [
            'uid' => $uid,
            'city_id' => $city_id,
            'shipping' => $shipping
        ];
    }

    //支付宝支付
    function alipay_pay()
    {
        if (request()->isPost()) {

            $result = $this->validate_pay();

            if (isset($result['error'])) {
                return $result;
            }

            $cart = osc_cart();

            //需要配送的
            if ($result['shipping']) {
                $weight = $cart->get_weight($result['uid']);
                $order['shipping_method'] = config('default_transport_id');
            } else {
                $weight = 0;
                $order['shipping_method'] = '';
            }

            $order['shipping_address_id'] = input('post.address_id');

            $order['payment_method'] = 'alipay';
            $order['weight'] = $weight;
            $order['shipping_city_id'] = $result['city_id'];
            $order['comment'] = input('post.comment');
            $order['uid'] = $result['uid'];

            $order = osc_order()->add_order('alipay', $order);

            $this->clear_cart($order['uid']);

            $config = payment_config('alipay');

            $alipay_config = array(
                "service" => 'alipay.wap.create.direct.pay.by.user',
                "partner" => $config['partner'],
                "seller_id" => $config['partner'],
                "key" => $config['key'],
                "payment_type" => 1,
                "notify_url" => request()->domain() . url('payment/alipay_notify'),
                "return_url" => request()->domain() . url('payment/alipay_return'),
                "_input_charset" => trim(strtolower(strtolower('utf-8'))),
                "out_trade_no" => $order['pay_order_no'],
                "subject" => $order['subject'],
                "total_fee" => $order['pay_total'],
                "show_url" => '',
                'transport' => 'http',
                'sign_type' => strtoupper('MD5'),
                //"app_pay"	=> "Y",//启用此参数能唤起钱包APP支付宝
                "body" => '',
            );


            $alipay = new \payment\alipay\Alipay($alipay_config, 'mobile');

            $url = $alipay->get_payurl();

            return ['success' => 1, 'type' => 'alipay', 'url' => $url];

        }
    }

    //支付宝异步通知
    function alipay_notify()
    {

        $alipay = new \payment\alipay\Alipay(payment_config('alipay'));

        $verify_result = $alipay->verifyNotify();

        if ($verify_result) {

            $post = input('post.');

            $order = Db::name('order')->where('order_num_alias', $post['out_trade_no'])->find();

            if ($post['trade_status'] == 'TRADE_FINISHED') {

            } elseif ($post['trade_status'] == 'TRADE_SUCCESS') {

                if ($order && ($order['order_status_id'] != config('paid_order_status_id'))) {

                    osc_order()->update_order($order['order_id']);

                    echo "success";

                } else {
                    echo "fail";
                }

            }

        } else {

            echo "fail";
        }
    }

    //支付宝同步通知
    function alipay_return()
    {

        $alipay = new \payment\alipay\Alipay(payment_config('alipay'));
        //对进入的参数进行远程数据判断
        $verify = $alipay->return_verify();

        if ($verify) {

            $get = input('param.');

            $order = Db::name('order')->where('order_num_alias', $get['out_trade_no'])->find();

            if ($order['order_status_id'] == config('paid_order_status_id')) {
                @header("Location: " . url('pay_success/index'));
                die;
            }

            if ($order && ($order['order_status_id'] != config('paid_order_status_id'))) {
                //支付完成
                if ($get['trade_status'] == 'TRADE_SUCCESS') {

                    osc_order()->update_order($order['order_id']);

                    @header("Location: " . url('pay_success/index'));
                }
            } else {
                die('订单不存在');
            }

        } else {
            die('支付失败');
        }
    }

    //支付宝，我的订单-》立即支付
    function alipay_repay()
    {

        $order_id = (int)input('param.order_id');

        $check = osc_order()->check_goods_quantity($order_id);

        if (isset($check['error'])) {
            return $check;
        }

        $order = Db::name('order')->where('order_id', $order_id)->find();

        if ($order && ($order['order_status_id'] != config('paid_order_status_id'))) {

            $config = payment_config('alipay');

            $alipay_config = array(
                "service" => 'alipay.wap.create.direct.pay.by.user',
                "partner" => $config['partner'],
                "seller_id" => $config['partner'],
                "key" => $config['key'],
                "payment_type" => 1,
                "notify_url" => request()->domain() . url('payment/alipay_notify'),
                "return_url" => request()->domain() . url('payment/alipay_return'),
                "_input_charset" => trim(strtolower(strtolower('utf-8'))),
                "out_trade_no" => $order['order_num_alias'],
                "subject" => $order['pay_subject'],
                "total_fee" => $order['total'],
                "show_url" => '',
                'transport' => 'http',
                'sign_type' => strtoupper('MD5'),
                //"app_pay"	=> "Y",//启用此参数能唤起钱包APP支付宝
                "body" => '',
            );


            $alipay = new \payment\alipay\Alipay($alipay_config, 'mobile');

            $url = $alipay->get_payurl();

            return ['success' => 1, 'url' => $url];
        } else {
            return ['error' => '订单已经支付'];
        }
    }

    //微信支付
    function weixin_pay()
    {
        if (request()->isPost()) {
            Db::startTrans();
            try {
                $uid = user('uid');
                $return['pay_total'] = input('pay_total');
                $return['subject'] = input('subject');
                $return['attach'] = input('attach');
                $return['goodsNum'] = input('goodsNum');
                $return['pay_order_no'] = build_order_no();
                $homeId = input('home_id');
                $gid = input('gid');
                //数据基础检验
                $buyGoodsCheck = Home::buyGoodsCheck($gid, $return['pay_total'], $return['goodsNum']);
                if (!$buyGoodsCheck) {
                    throw new Exception('数据有误');
                }
                $orderData = array(
                    'uid' => $uid,
                    'gid' => $gid,
                    'home_id' => $homeId,
                    'pay_order_no' => $return['pay_order_no'],
                    'pay_amount' => input('pay_total'),
                    'pay_type' => PayOrder::WEI_PAY,
                    'order_type' => $return['attach'],
                    'buy_num' => $return['goodsNum'],
                );
                //先生成订单
                $a = PayOrder::addOrder($orderData);
                if (!$a) {
                    throw new Exception('创建订单失败');
                }
                //判断以及减少商品份额新增减少该项份额
                Home::changePortion($homeId, $return['goodsNum']);
                $payResult = WeixinPay::getBizPackage($return);
                Db::commit();
                return $payResult;
            } catch (\Exception $e) {
                Db::rollback();
                return json(['ret_code' => 11, 'ret_msg' => $e->getMessage()]);
            }

        }
    }

    //微信jssdk回调
    public function jsskd_notify()
    {
        Db::startTrans();
        try {
            if (wechat()->checkPaySign()) {

                $sourceStr = file_get_contents('php://input');

                // 读取数据
                $postObj = simplexml_load_string($sourceStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                Db::name('test')->insert(array(
                    'info' => json_encode($postObj),
                ));
                if (!$postObj) {
                    throw new Exception('出错');
                } else {
                    $returnData = json_decode($postObj, true);
                    OrderProcess::pay_notify($returnData);
                    Db::commit();
                    echo "<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>";
                }
            } else {
                throw new Exception('出错');
            }
        } catch (Exception $e) {
            Db::rollback();
            echo "<xml><return_code><![CDATA[FAIL]]></return_code></xml>";
        }
    }

    /**
     * 取消或订单出错
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function cancel_order()
    {
        $uid = user('uid');
        $return['gid'] = input('gid');
        $return['order_no'] = input('order_no');
        $return['status'] = input('status');
        PayOrder::editStatus($return['order_no'], $return['status']);
        //删除减去该用户购买的份额
        Home::changePortion(input('home_id'), input('goodsNum'), Home::REDUCE_NUM);
    }
}

?>