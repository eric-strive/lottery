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

namespace osc\mobile\service;

use osc\admin\model\duobaoRecord;
use osc\admin\model\Goods;
use osc\admin\model\Home;
use osc\admin\model\PayOrder;
use think\Db;
use think\Exception;

//订单处理
class OrderProcess
{
    /**
     * 支付回调
     * @param $returnData
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function pay_notify($returnData)
    {
        $orderNo = $returnData['out_trade_no'];
        $cashFee = $returnData['cash_fee'];
        $orderInfo = PayOrder::orderInfo($orderNo);
        if (empty($orderInfo) || $orderInfo['status'] == PayOrder::STATUS_SUCCESS_PAY) {
            throw new Exception('出错');
        }
        $attach = isset($returnData['attach']) ? $returnData['attach'] : '';
        switch ($attach) {
            case '1':
                $homeId = $orderInfo['home_id'];
                if (empty($homeId)) {
                    throw new Exception('订单有误');
                }
                self::addNumber($orderInfo, $homeId);
        }
        PayOrder::savePayInfo(array(
            'uid' => $orderInfo['uid'],
            'gid' => $orderInfo['gid'],
            'home_id' => $orderInfo['home_id'],
            'amount' => $cashFee,
            'order_no' => $orderNo,
            'pay_type' => 1,
            'pay_time' => date('Y-m-d H:i:s'),
        ));
        PayOrder::editStatus($returnData['out_trade_no'], PayOrder::STATUS_SUCCESS_PAY);
    }

    /**
     * 新增多包数据
     * @param $orderInfo
     * @param $homeId
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function addNumber($orderInfo, $homeId)
    {
        $homeInfo = Home::getHomeInfo($homeId, true);
        if (empty($homeInfo)) {
            throw new Exception('信息出错');
        }
        $lastNum = duobaoRecord::getLastNum($homeId);
        $lastNum = empty($lastNum) ? 0 : intval($lastNum);
        $buyNum = $orderInfo['buy_num'];
        $sumNum = $lastNum + $buyNum;
        if ($sumNum > $homeInfo['lottery_drifts'] || $sumNum > $homeInfo['goods_buy_num']) {
            throw new Exception('订单信息有误');
        }
        $duobaoData = array();
        $startNum = $lastNum + 1;
        for ($startNum; $startNum <= $sumNum; $startNum++) {
            $duobaoData[] = array(
                'dduonum' => $startNum,
                'home_id' => $homeId,
                'uid' => $orderInfo['uid'],
                'gid' => $orderInfo['gid'],
                'dlasttime' => date('Y-m-d H:i:s'),
            );
        }
        //批量保存
        $saveResult = duobaoRecord::batchData($duobaoData);
        if (!$saveResult) {
            throw new Exception('保存夺宝数据出错');
        }
        //如果商品投满
        if (intval($sumNum) == intval($homeInfo['lottery_drifts'])) {
            self::homeFull($homeInfo);
        }
    }

    /**
     * 满房
     * @param $homeInfo
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function homeFull($homeInfo)
    {
        //获取中奖号码
        $lottery_timestamp = time();
        $lottery_drifts = $homeInfo['lottery_drifts'];
        if (empty($homeInfo['lottery_num'])) {
            list($lottery_num, $rand_num) = arithmetic($lottery_timestamp, $lottery_drifts);
        } else {
            $lottery_num = $homeInfo['lottery_num'];
            $rand_num = re_arithmetic($lottery_timestamp, $lottery_num, $lottery_drifts);
        }
        //根据中奖号码获取中奖用户
        $dInfo = duobaoRecord::getLotteryInfo($lottery_num, $homeInfo['id']);
        //修改房间状态
        $return = Home::homeFull($homeInfo['id'], $dInfo['uid'], $lottery_num, $rand_num, $lottery_timestamp);
        if ($return === false) {
            throw new Exception('数据修改出错');
        }
        if (empty($homeInfo['home_num'])) {
            $data = array(
                'periods' => $homeInfo['goods_periods'],
                'gid' => $homeInfo['gid'],
            );
            $a = Home::add_home($data);
            if (!$a['home_id']) {
                throw new Exception('开新房间出错');
            }
        }
        //库存减一
        Goods::buyGoods($homeInfo['gid']);
    }
}

?>