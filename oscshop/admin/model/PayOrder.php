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
use think\Exception;
use think\exception\ErrorException;

class PayOrder
{
    //支付方式
    const WEI_PAY = 1;
    const JINDOU_PAY = 2;
    const ALI_PAY = 3;

    //支付状态
    const STATUS_NOT_PAY = 0;
    const STATUS_SUCCESS_PAY = 1;
    const STATUS_FAIL_PAY = 2;
    const STATUS_CANCEL_PAY = 3;

    //订单类型
    const HOME_TYPE = 1;
    const LUCK_BUY_TYPE = 2;
    const RECHARGE_TYPE = 3;
    const GAME_TYPE = 4;

    /**
     * 新增订单
     *
     * @param $orderInfo
     *
     * @return int|string
     * @throws ErrorException*@throws \think\Exception
     */
    public static function addOrder($orderInfo)
    {
        if (empty($orderInfo['uid'])) {
            throw new Exception('用户不存在');
        }
        $data = array(
            'uid' => $orderInfo['uid'],
            'gid' => $orderInfo['gid'],
            'buy_num' => $orderInfo['buy_num'],
            'home_id' => $orderInfo['home_id'],
            'pay_amount' => $orderInfo['pay_amount'],
            'pay_order_no' => $orderInfo['pay_order_no'],
            'pay_type' => $orderInfo['pay_type'],
            'order_type' => $orderInfo['order_type'],
            'create_at' => date('Y-m-d H:i:s'),
        );
        return Db::name('pay_order')->insert($data, false, true);
    }

    /**
     * 修改订单状态
     * @param $orderNo
     * @param $status
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function editStatus($orderNo, $status)
    {
        $return = Db::name('pay_order')
            ->where('pay_order_no="' . $orderNo . '"')
            ->update(array('status' => $status, 'update_at' => date('Y-m-d H:i:s')));
        return $return;
    }

    /**
     * 获取订单信息
     * @param $orderNo
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function orderInfo($orderNo)
    {
        return Db::name('pay_order')
            ->where('pay_order_no="' . $orderNo . '"')
            ->find();
    }
    public static function savePayInfo($data){
       return Db::name('pay_record')->insert($data);
    }
}

?>