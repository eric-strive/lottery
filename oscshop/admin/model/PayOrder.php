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
    const ORDER_TYPE = array(
        self::COMMON_TYPE => ''
    );

    /**
     * 新增订单
     * @param $orderInfo
     * @return int|string
     * @throws ErrorException
     */
    public static function addOrder($orderInfo)
    {
        if (empty($orderInfo['uid'])) {
            throw new ErrorException('用户不存在');
        }
        $data = [
            'uid' => $orderInfo['uid'],
            'gid' => $orderInfo['gid'],
            'home_id' => $orderInfo['home_id'],
            'pay_amount' => $orderInfo['pay_amount'],
            'pay_order_no' => $orderInfo['pay_order_no'],
            'pay_type' => $orderInfo['pay_type'],
            'order_type' => $orderInfo['order_type'],
            'create_at' => date('Y-m-d H:i:s'),
        ];
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
            ->update(array('status' => $status));
        return $return;
    }
}

?>