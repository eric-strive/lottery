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
use osc\admin\model\GameHome;
use osc\admin\model\Goods;
use osc\admin\model\Home;
use osc\admin\model\LuckRecord;
use osc\admin\model\Member;
use osc\admin\model\PayOrder;
use think\Db;
use think\Exception;

//订单处理
class OrderProcess
{
    /**
     * 支付回调
     *
     * @param $postObj
     *
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     */
    public static function pay_notify($postObj)
    {
        $orderNo   = $postObj->out_trade_no;
        $cashFee   = $postObj->cash_fee;
        $orderInfo = PayOrder::orderInfo($orderNo);
        if (empty($orderInfo) || $orderInfo['status'] == PayOrder::STATUS_SUCCESS_PAY) {
            throw new Exception('出错');
        }
        $rechargeAmount = $cashFee / 100;
        $attach         = isset($postObj->attach) ? $postObj->attach : '';
        switch ($attach) {
            case '1':
                $homeId = $orderInfo['home_id'];
                if (empty($homeId)) {
                    throw new Exception('订单有误');
                }
                self::addNumber($orderInfo, $homeId);
                break;
            case '2':
                LuckRecord::setStatus($orderNo);
                break;
            case '3':
                Member::addBalance($orderInfo['uid'], $rechargeAmount);
                Member::addBalanceRecord([
                    'uid'         => $orderInfo['uid'],
                    'amount'      => $rechargeAmount,
                    'description' => '用户充值',
                    'prefix'      => 1,
                    'create_time' => time(),
                    'type'        => 3,
                    'order_no'    => $orderNo,
                ]);
                break;
            case '4'://开始游戏房间
                $s = GameHome::setHomeStatus($orderInfo['home_id']);

                if ($s === false) {
                    throw new Exception('修改房间状态出错');
                }
                $recordInfo = GameHome::getRecordInfo($orderInfo['home_id'], $orderInfo['uid']);
                if (empty($recordInfo)) {
                    GameHome::add_game_record([
                        'uid'          => $orderInfo['uid'],
                        'game_home_id' => $orderInfo['home_id'],
                        'pay_amount'   => $rechargeAmount,
                        'pay_status'   => 1,
                        'grade'        => 0,
                        'type'         => 1,
                        'game_key'     => GameHome::GAME_FROG,
                        'create_at'    => date('Y-m-d H:i:s'),
                    ]);
                }
                break;
            case '5'://进入游戏房间
                GameHome::setRecordStatus($orderInfo['home_id'], $orderInfo['uid']);
                GameHome::addParameter($orderInfo['home_id']);
                break;
        }
        self::addOtherInfo($orderInfo, $rechargeAmount, $attach);
        PayOrder::editStatus($orderNo, PayOrder::STATUS_SUCCESS_PAY);
    }

    /**
     * @param $orderInfo
     * @param $cashFee
     * @param $attach
     *
     * @return bool
     * @throws Exception
     */
    public static function addOtherInfo($orderInfo, $cashFee, $attach)
    {
        $home_id = empty($orderInfo['home_id']) ? 0 : $orderInfo['home_id'];
        $gid     = empty($orderInfo['gid']) ? 0 : $orderInfo['gid'];
        PayOrder::savePayInfo([
            'uid'      => $orderInfo['uid'],
            'gid'      => $gid,
            'home_id'  => $home_id,
            'amount'   => $cashFee,
            'order_no' => $orderInfo['pay_order_no'],
            'pay_type' => 1,
            'pay_time' => date('Y-m-d H:i:s'),
        ]);
        $description = '未知';
        switch ($attach) {
            case '1':
                $description = sprintf('夺宝花费%s获得', $cashFee);
                break;
            case '2':
                $description = sprintf('幸运购花费%s获得', $cashFee);
                break;
            case '3':
                return true;
            case '4':
                $description = sprintf('开游戏房间花费%s获得', $cashFee);
                break;
            case '5':
                $description = sprintf('参与游戏花费%s获得', $cashFee);
                break;
                break;
        }
        Member::addPoints($orderInfo['uid'], $cashFee);
        Member::addPointsRecord([
            'uid'         => $orderInfo['uid'],
            'home_id'     => $home_id,
            'points'      => $cashFee,
            'order_no'    => $orderInfo['pay_order_no'],
            'prefix'      => 1,
            'type'        => 1,
            'creat_time'  => time(),
            'description' => $description,
        ]);
    }

    /**
     * 新增多包数据
     *
     * @param $orderInfo
     * @param $homeId
     *
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
        $buyNum  = $orderInfo['buy_num'];
        $sumNum  = $lastNum + $buyNum;
        if ($sumNum > $homeInfo['lottery_drifts'] || $sumNum > $homeInfo['goods_buy_num']) {
            throw new Exception('订单信息有误');
        }
        $duobaoData = [];
        $startNum   = $lastNum + 1;
        for ($startNum; $startNum <= $sumNum; $startNum++) {
            $duobaoData[] = [
                'dduonum'   => $startNum,
                'home_id'   => $homeId,
                'uid'       => $orderInfo['uid'],
                'gid'       => $orderInfo['gid'],
                'dlasttime' => date('Y-m-d H:i:s'),
            ];
        }
        //批量保存
        $saveResult = duobaoRecord::batchData($duobaoData);
        if (!$saveResult) {
            throw new Exception('保存夺宝数据出错');
        }
        //新增购买记录
        Db::name('home_record')->insert([
            'home_id'   => $homeId,
            'uid'       => $orderInfo['uid'],
            'gid'       => $orderInfo['gid'],
            'pay_type'  => $orderInfo['pay_type'],
            'home_name' => $homeInfo['home_name'],
            'start_num' => $lastNum + 1,
            'end_num'   => $startNum - 1,
        ]);
        //如果商品投满
        if (intval($sumNum) == intval($homeInfo['lottery_drifts'])) {
            self::homeFull($homeInfo);
        }
    }

    /**
     * 满房
     *
     * @param $homeInfo
     *
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
        $lottery_drifts    = $homeInfo['lottery_drifts'];
        if ($homeInfo['lottery_num'] > 0) {
            $lottery_num = $homeInfo['lottery_num'];
            $rand_num    = re_arithmetic($lottery_timestamp, $lottery_num, $lottery_drifts);
        } else {
            list($lottery_num, $rand_num) = arithmetic($lottery_timestamp, $lottery_drifts);
        }

        //根据中奖号码获取中奖用户
        $dInfo = duobaoRecord::getLotteryInfo($lottery_num, $homeInfo['id']);
        //修改房间状态
        $return = Home::homeFull($homeInfo['id'], $dInfo['uid'], $lottery_num, $rand_num, $lottery_timestamp);
        if ($return === false) {
            throw new Exception('数据修改出错');
        }
        if (empty($homeInfo['home_num'])) {
            $data = [
                'periods' => $homeInfo['goods_periods'],
                'gid'     => $homeInfo['gid'],
            ];
            $a    = Home::add_home($data);
            if (!$a['home_id']) {
                throw new Exception('开新房间出错');
            }
        }
        //库存减一
        Goods::buyGoods($homeInfo['gid']);
    }
}

?>