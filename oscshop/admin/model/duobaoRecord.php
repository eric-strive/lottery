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

namespace osc\admin\model;

use think\Db;

class duobaoRecord
{

    public function validate($data)
    {

        $error = array();
        if (empty($data['dduonum'])) {
            $error['error'] = '号码必填';
        }
        if ($error) {
            return $error;
        }

    }

    /**
     * 获取商品购买的百分比
     * @param $periods
     * @param $gid
     * @param int $type
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function get_duobao_num($periods, $gid, $type = 0)
    {
        $query = Db::name('home')
            ->where('type', $type)
            ->where('gid', $gid);
        if ($type == 1) {
            $query->where('home_num', $periods);
        } else {
            $query->where('goods_periods', $periods);
        }
        $date = $query->find();
        return self::get_periods($date);
    }

    public static function get_periods($date)
    {
        if (empty($date)) {
            return '0%';
        }
        return sprintf("%01.0f", $date['goods_buy_num'] / $date['lottery_drifts'] * 100) . '%';
    }

    /**
     * 获取该房间最后一次夺宝记录
     * @param $home_id
     * @return mixed
     */
    public static function getLastNum($homeId)
    {
        return Db::name('duobao_record')->lock(true)
            ->where('home_id', $homeId)
            ->order('id desc')
            ->value('dduonum');
    }

    public static function addNumber($homeId, $orderInfo)
    {
        $lastNum = self::getLastNum($homeId);
        $lastNum = empty($lastNum) ? 1 : intval($lastNum);
        $buyNum = $orderInfo['buy_num'];
        $sumNum = $lastNum + $buyNum;
        $duobaoData = array();
        for ($lastNum; $lastNum < $sumNum; $lastNum++) {
            $duobaoData[] = array(
                'dduonum' => $lastNum,
                'home_id' => $homeId,
                'uid' => $orderInfo['uid'],
                'gid' => $orderInfo['gid'],
                'dlasttime' => date('Y-m-d H:i:s'),
            );
        }
        //批量保存
        $saveResult = self::batchData($duobaoData);
        if (!$saveResult) {

        }
    }

    public static function batchData($duobaoData)
    {
        return Db::name('duobao_record')->insertAll($duobaoData);
    }

    /**
     * @param $lottery_num
     * @param $homeId
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getLotteryInfo($lottery_num, $homeId)
    {
        return Db::name('duobao_record')->where(array(
            'dduonum' => $lottery_num,
            'home_id' => $homeId
        ))->find();
    }
}

?>