<?php

namespace osc\admin\model;

use think\Db;
use think\Exception;
use think\exception\ErrorException;

class Member
{
    const ADD = 1;
    const REDUCE = 2;

    /**
     * 添加积分
     * @param $uid
     * @param $points
     * @throws Exception
     */
    public static function addPoints($uid, $points, $type = self::ADD)
    {
        if ($type == self::ADD) {
            Db::name('member')->where('uid', $uid)
                ->setInc('points', $points);
        } else {
            Db::name('member')->where('uid', $uid)
                ->setDec('points', $points);
        }
    }

    public static function addBalance($uid, $balance, $type = self::ADD)
    {
        if ($type == self::ADD) {
            Db::name('member')->where('uid', $uid)
                ->setInc('balance', $balance);
        } else {
            Db::name('member')->where('uid', $uid)
                ->setDec('balance', $balance);
        }
    }

    public static function addPointsRecord($data)
    {
        Db::name('points')->insert($data);
    }

    public static function addBalanceRecord($data)
    {
        Db::name('balance')->insert($data);
    }

    /**
     * @param $date
     * @return bool
     * @throws Exception
     */
    public static function pointsProcess($date)
    {
        $resultPoints = intval($date['add_points']) - intval($date['reduce_points']);
        if ($resultPoints == 0) {
            return true;
        }
        $pointsType = $resultPoints > 0 ? self::ADD : self::REDUCE;
        self::addPoints($date['uid'], abs($resultPoints), $pointsType);
        self::addPointsRecord(array(
            'uid' => $date['uid'],
            'points' => abs($resultPoints),
            'description' => '管理员后台充值',
            'prefix' => $pointsType,
            'creat_time' => time(),
            'type' => 2,
            'admin_id' => UID,
        ));
    }

    public static function balanceProcess($date)
    {
        $resultBalance = intval($date['add_balance']) - intval($date['reduce_balance']);
        if ($resultBalance == 0) {
            return true;
        }
        $pointsType = $resultBalance > 0 ? self::ADD : self::REDUCE;
        self::addBalance($date['uid'], abs($resultBalance), $pointsType);
        self::addBalanceRecord(array(
            'uid' => $date['uid'],
            'amount' => abs($resultBalance),
            'description' => '管理员后台充值',
            'prefix' => $pointsType,
            'create_time' => time(),
            'type' => 2,
            'admin_id' => UID,
        ));
    }

    public static function giveBalanceLuck($homeInfo)
    {
        $goodsInfo = Db::name('goods')->find($homeInfo['gid']);
        if ($goodsInfo['return_venosa'] > 0) {
            self::addBalance($homeInfo['lottery_uid'], $goodsInfo['return_venosa']);
            self::addBalanceRecord(array(
                'uid' => $homeInfo['lottery_uid'],
                'amount' => $goodsInfo['return_venosa'],
                'description' => '用户夺宝获取返还金豆',
                'prefix' => 1,
                'create_time' => time(),
                'type' => 4,
                'admin_id' => UID,
            ));
        }
    }

    public static function getMemberInfo($uid, $lock = false)
    {
        return Db::name('member')->lock($lock)->find($uid);
    }
}

?>