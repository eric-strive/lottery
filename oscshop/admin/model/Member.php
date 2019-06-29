<?php

namespace osc\admin\model;

use think\Db;
use think\Exception;
use think\exception\ErrorException;

class Member
{
    const ADD    = 1;
    const REDUCE = 2;

    /**
     * 添加积分
     *
     * @param $uid
     * @param $points
     *
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
        return Db::name('balance')->insert($data);
    }

    /**
     * @param $date
     *
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
        self::addPointsRecord([
            'uid'         => $date['uid'],
            'points'      => abs($resultPoints),
            'description' => '管理员后台充值',
            'prefix'      => $pointsType,
            'creat_time'  => time(),
            'type'        => 2,
            'admin_id'    => UID,
        ]);
    }

    public static function balanceProcess($date)
    {
        $resultBalance = intval($date['add_balance']) - intval($date['reduce_balance']);
        if ($resultBalance == 0) {
            return true;
        }
        $pointsType = $resultBalance > 0 ? self::ADD : self::REDUCE;
        self::addBalance($date['uid'], abs($resultBalance), $pointsType);
        self::addBalanceRecord([
            'uid'         => $date['uid'],
            'amount'      => abs($resultBalance),
            'description' => '管理员后台充值',
            'prefix'      => $pointsType,
            'create_time' => time(),
            'type'        => 2,
            'admin_id'    => UID,
        ]);
    }

    /**
     * 金豆处理
     *
     * @param     $uid
     * @param     $return_venosa
     * @param int $type
     * @param int $reduce
     */
    public static function giveBalanceLuck($uid, $return_venosa, $type = 4, $reduce = self::ADD)
    {
        $description = '';
        switch ($type) {
            case 4:
                $description = '用户夺宝获取返还金豆';
                break;
            case 5:
                $description = '用户幸运购花费';
                break;
            case 7:
                $description = '房间对战胜利';
                break;
            case 8:
                $description = '幸运购中奖';
                break;
            case 9:
                $description = '用户购买商品花费';
        }
        if ($return_venosa > 0) {
            self::addBalance($uid, $return_venosa, $reduce);
            self::addBalanceRecord([
                'uid'         => $uid,
                'amount'      => $return_venosa,
                'description' => $description,
                'prefix'      => $reduce,
                'create_time' => time(),
                'type'        => $type,
                'admin_id'    => 1,
            ]);
        }
    }

    public static function getMemberInfo($uid, $lock = false)
    {
        return Db::name('member')->lock($lock)->find($uid);
    }
}

?>