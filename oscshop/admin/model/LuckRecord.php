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

class LuckRecord
{
    //支付方式
    const WEI_PAY    = 1;
    const JINDOU_PAY = 2;
    const ALI_PAY    = 3;

    //支付状态
    const STATUS_NOT_PAY     = 0;
    const STATUS_SUCCESS_PAY = 1;
    const STATUS_FAIL_PAY    = 2;
    const STATUS_CANCEL_PAY  = 3;

    const  NOT_LOTTERY = 0;
    const  LOTTERY     = 1;

    /**
     * 新增幸运购记录
     *
     * @param $orderInfo
     *
     * @return int|string
     * @throws Exception
     */
    public static function addLuckRecord($orderInfo)
    {
        if (empty($orderInfo['uid'])) {
            throw new Exception('用户不存在');
        }
        $data = [
            'uid'       => $orderInfo['uid'],
            'gid'       => $orderInfo['gid'],
            'amount'    => $orderInfo['pay_amount'],
            'order_no'  => $orderInfo['pay_order_no'],
            'create_at' => date('Y-m-d H:i:s'),
        ];

        return Db::name('luck_record')->insert($data, false, true);
    }

    /**
     * 获取该用户总共抽奖花的金额
     *
     * @param $gid
     * @param $uid
     *
     * @return float|int
     */
    public static function recordSum($gid, $uid)
    {
        return Db::name('luck_record')
            ->where([
                'gid'        => $gid,
                //                'uid'        => $uid,
                'is_process' => self::STATUS_NOT_PAY,
                'status'     => self::STATUS_SUCCESS_PAY,
            ])
            ->lock(true)
            ->sum('amount');
    }

    public static function luckInfo($order_no)
    {
        return Db::name('luck_record')
            ->where([
                'order_no' => $order_no,
            ])
            ->find();
    }

    /**
     * 修改状态
     *
     * @param $status
     * @param $order_no
     *
     * @return int|string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function setStatus($order_no, $status = self::STATUS_SUCCESS_PAY)
    {
        return Db::name('luck_record')
            ->where([
                'order_no' => $order_no,
            ])
            ->update([
                'status'    => $status,
                'update_at' => date('Y-m-d H:i:s'),
            ]);
    }

    /**
     * @param     $id
     * @param int $status
     *
     * @return int|string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function setDraw($id, $status = self::LOTTERY)
    {
        return Db::name('luck_record')
            ->where([
                'luck_record_id' => $id,
            ])
            ->update([
                'is_draw'   => $status,
                'update_at' => date('Y-m-d H:i:s'),
            ]);
    }

    /**
     * @param $order_no
     *
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getInfo($order_no)
    {
        return Db::name('luck_record')
            ->where([
                'order_no' => $order_no,
            ])->find();
    }

    /**
     * 幸运购中奖
     *
     * @param $order_no
     */
    public static function setLottery($order_no)
    {
        Db::transaction(function () use ($order_no) {
            $lottery     = Db::name('luck_record')
                ->where([
                    'order_no' => $order_no,
                ])->update([
                    'is_lottery' => 1,
                ]);
            $luckInfo    = self::getInfo($order_no);
            $luckSuccess = Db::name('luck_record')
                ->where([
                    'luck_record_id' => ['<=', $luckInfo["luck_record_id"]],
                    'gid'            => $luckInfo['gid'],
                    //                    'uid'            => $luckInfo['uid'],
                ])
                ->update([
                    'is_process' => 1,
                ]);
            if ($lottery === false || $luckSuccess === false) {
                throw new Exception('状态出错');
            }
        });
    }

    /**
     * 幸运购获得金额
     *
     * @param $uid
     * @param $amount
     * @param $luckRecordId
     *
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public static function getAmount($uid, $amount, $luckRecordId)
    {
        Member::giveBalanceLuck($uid, $amount, 8);
        self::setDraw($luckRecordId);
    }

    public static function getRecord($uid = null, $isLottery = null, $is_draw = null, $count = 20)
    {
        $data = input('get.');

        $query = Db::view('LuckRecord', '*')
            ->view('Goods', 'name,return_venosa,price', 'LuckRecord.gid=Goods.goods_id')
            ->view('GoodsImage', 'image', 'LuckRecord.gid=GoodsImage.goods_id')
            ->where('LuckRecord.status', self::STATUS_SUCCESS_PAY);
        if ($uid && $uid !== true) {
            $query->where('LuckRecord.uid', $uid)
                ->view('Member', 'nickname', 'Member.uid=LuckRecord.uid', 'left');
        } else {
            $query->view('Member', 'nickname', 'Member.uid=LuckRecord.uid', 'left');
        }
        if (isset($data['is_lottery'])) {
            $query->where([
                'LuckRecord.is_lottery' => $data['is_lottery'] - 1,
            ]);
        }
        if (isset($data['is_draw'])) {
            $query->where([
                'LuckRecord.is_draw' => $data['is_draw'] - 1,
            ]);
        }
        if (isset($data['nickname'])) {
            $query->where([
                'nickname' => ['like', '%' . $data['nickname'] . '%'],
            ]);
        }
        if ($isLottery !== null) {
            $query->where([
                'LuckRecord.is_lottery' => $isLottery,
            ]);
        }
        if ($is_draw !== null) {
            $query->where([
                'LuckRecord.is_draw' => $is_draw,
            ]);
        }

        if ($uid) {
            return $query->order('LuckRecord.status asc,LuckRecord.is_draw asc,LuckRecord.luck_record_id desc')
                ->select();
        }

        return $query->order('LuckRecord.status asc,LuckRecord.is_draw asc,LuckRecord.luck_record_id desc')
            ->paginate($count, false);
    }
}

?>