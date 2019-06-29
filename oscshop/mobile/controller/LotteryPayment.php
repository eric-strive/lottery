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
use osc\admin\model\Goods as GoodsModel;
use osc\admin\model\LuckRecord;
use osc\admin\model\Member;
use osc\admin\model\PayOrder;
use osc\admin\service\GameHomeService;
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
    //微信支付
    function weixin_pay()
    {
        if (request()->isPost()) {
            Db::startTrans();
            try {
                $uid                    = user('uid');
                $return['pay_total']    = input('pay_total', 0);
                $return['subject']      = input('subject', 0);
                $return['attach']       = input('attach', 0);
                $return['goodsNum']     = input('goodsNum', 0);
                $return['pay_order_no'] = build_order_no();
                $homeId                 = input('home_id', 0);
                $gid                    = input('gid', 0);
                $orderData              = [
                    'uid'          => $uid,
                    'gid'          => $gid,
                    'record_id'    => 0,
                    'home_id'      => $homeId,
                    'pay_order_no' => $return['pay_order_no'],
                    'pay_amount'   => input('pay_total'),
                    'pay_type'     => PayOrder::WEI_PAY,
                    'order_type'   => $return['attach'],
                    'buy_num'      => $return['goodsNum'],
                ];
                //数据基础检验
                switch ($return['attach']) {
                    case '1':
                        $buyGoodsCheck = Home::buyGoodsCheck($gid, $return['pay_total'], $return['goodsNum']);
                        if (!$buyGoodsCheck) {
                            throw new Exception('数据有误');
                        }
                        //判断以及减少商品份额新增减少该项份额
                        Home::changePortion($homeId, $return['goodsNum']);
                        break;
                    case '2':
                        LuckRecord::addLuckRecord($orderData);
                        break;
                    case '3':
                        //开设房间
                        //                        GameHomeService::set_game_home();
                        break;
                    case '4':
                        //开设房间
                        $orderData['home_id'] = GameHomeService::set_game_home(0);
                        break;
                    case '5':
                        //参与青蛙游戏
                        $record_id              = GameHomeService::participateGame(input('post.'));
                        $orderData['record_id'] = $record_id;
                        break;
                }

                //先生成订单
                $a = PayOrder::addOrder($orderData);
                if (!$a) {
                    throw new Exception('创建订单失败');
                }
                $payResult            = WeixinPay::getBizPackage($return);
                $payResult['home_id'] = $homeId;
                Db::commit();

                return json($payResult);
            } catch (\Exception $e) {
                Db::rollback();

                return json(['ret_code' => 11, 'ret_msg' => $e->getMessage()]);
            }

        }
    }

    //微信jssdk回调
    public function jsskd_notify()
    {
        $sourceStr = file_get_contents('php://input');
        // 读取数据
        $postObj = simplexml_load_string($sourceStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        Db::name('test')->insert([
            'info' => json_encode($postObj),
        ]);
        Db::startTrans();
        try {
            if (wechat()->checkPaySign()) {
                //                $sourceStr = file_get_contents('php://input');
                //                // 读取数据
                //                $postObj = simplexml_load_string($sourceStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                if (!$postObj) {
                    throw new Exception('出错');
                } else {
                    OrderProcess::pay_notify($postObj);
                    Db::commit();
                    echo "<xml><return_code><![CDATA[SUCCESS]]></return_code></xml>";
                }
            } else {
                throw new Exception('出错');
            }
        } catch (Exception $e) {
            Db::rollback();
            Db::name('test')->insert([
                'info' => json_encode($e->getMessage() . $e->getFile() . $e->getLine()),
            ]);
            echo "<xml><return_code><![CDATA[FAIL]]></return_code></xml>";
        }
    }

    /**
     * 取消或订单出错
     *
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public function cancel_order()
    {
        $return['gid']      = input('gid');
        $return['order_no'] = input('order_no');
        $return['status']   = input('status');
        PayOrder::editStatus($return['order_no'], $return['status']);
        //删除减去该用户购买的份额
        Home::changePortion(input('home_id'), input('goodsNum'), Home::REDUCE_NUM);
    }

    function balance_pay()
    {
        if (request()->isPost()) {
            Db::startTrans();
            try {
                $isLottery              = false;
                $uid                    = user('uid');
                $return['pay_total']    = input('pay_total', 0);
                $return['subject']      = input('subject', 0);
                $return['attach']       = input('attach', 0);
                $return['goodsNum']     = input('goodsNum', 0);
                $return['pay_order_no'] = build_order_no();
                $homeId                 = input('home_id', 0);
                $gid                    = input('gid', 0);
                $orderData              = [
                    'uid'          => $uid,
                    'gid'          => $gid,
                    'home_id'      => $homeId,
                    'pay_order_no' => $return['pay_order_no'],
                    'pay_amount'   => input('pay_total'),
                    'pay_type'     => PayOrder::JINDOU_PAY,
                    'order_type'   => $return['attach'],
                    'buy_num'      => $return['goodsNum'],
                ];
                //先生成订单
                $a = PayOrder::addOrder($orderData);
                if (!$a) {
                    throw new Exception('创建订单失败');
                }
                $userInfo = Member::getMemberInfo($uid, true);
                if ($userInfo['balance'] < $return['pay_total']) {
                    return json(['ret_code' => 3, 'ret_msg' => '金豆不足，请先充值！']);
                }
                //数据基础检验
                switch ($return['attach']) {
                    case '1':
                        $buyGoodsCheck = Home::buyGoodsCheck($gid, $return['pay_total'], $return['goodsNum']);
                        if (!$buyGoodsCheck) {
                            throw new Exception('数据有误');
                        }
                        //判断以及减少商品份额新增减少该项份额
                        Home::changePortion($homeId, $return['goodsNum']);
                        //支付处理
                        WeixinPay::balancePay($return, $homeId, $uid);
                        break;
                    case '2'://幸运购
                        LuckRecord::addLuckRecord($orderData);
                        WeixinPay::luckBalancePay($return['pay_order_no'], $return['pay_total'], $uid);
                        $goodsInfo = GoodsModel::getGoodsInfo($gid);

                        $recordSum = LuckRecord::recordSum($gid, $uid);
                        if ($recordSum >= $goodsInfo['lotter_price']) {
                            //中奖了
                            LuckRecord::setLottery($return['pay_order_no']);
                            $isLottery = true;
                        }
                        break;
                    case '4':
                        $homeId = GameHomeService::set_game_home();
                        GameHomeService::subBalance($orderData);
                        break;
                    case '5':
                        //参与青蛙游戏
                        GameHomeService::participateGame(input('post.'), 1);
                        GameHomeService::subBalance($orderData);
                        break;
                    case '9':
                        //购买商品
                        WeixinPay::goodsBug();
                        break;

                }
                Db::commit();

                return json([
                    'ret_code'  => 1,
                    'homeId'    => $homeId,
                    'order_no'  => $return['pay_order_no'],
                    'isLottery' => $isLottery,
                ]);
            } catch (\Exception $e) {
                Db::rollback();

                return json(['ret_code' => 11, 'ret_msg' => $e->getMessage()]);
            }

        }
    }
}

?>