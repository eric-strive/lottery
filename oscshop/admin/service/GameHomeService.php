<?php

namespace osc\admin\service;

use osc\admin\model\GameHome;
use osc\admin\model\Member;
use osc\admin\model\PayOrder;
use think\Db;
use think\Exception;

//游戏房间
class GameHomeService
{
    /**
     * 开设房间
     *
     * @param int $status
     *
     * @return array|int|string
     * @throws \think\Exception
     */
    public static function set_game_home($status = 1)
    {
        if (request()->isPost()) {
            $uid  = user('uid');
            $data = input('post.');
            //提现的额度
            if (empty($data['game_home_name'])) {
                throw new Exception('房间名不能为空');
            }
            if (empty($data['game_home_password'])) {
                return ['error' => '密码不能为空'];
            }
            //负数
            if ($data['pay_total'] < 0.1) {
                throw new Exception('金额不能小于5');
            }
            if ($data['game_home_number_people'] < 2 || $data['game_home_number_people'] > 10) {
                throw new Exception('人数2～10人');
            }
            $data['pay_amount'] = $data['pay_total'];
            unset($data['pay_total']);
            unset($data['subject']);
            unset($data['attach']);
            $data['create_at']        = date('Y-m-s H:i:s');
            $data['game_home_uid']    = $uid;
            $data['game_home_status'] = $status;
            $data['sign']             = build_order_no();

            $homeId = GameHome::add_game_home($data);
            if (!$homeId) {
                throw new Exception('失败');
            }
            if ($status == 1) {
                GameHome::add_game_record([
                    'uid'          => $uid,
                    'game_home_id' => $homeId,
                    'pay_amount'   => $data['pay_amount'],
                    'pay_status'   => $status,
                    'grade'        => 0,
                    'type'         => 1,
                    'game_key'     => GameHome::GAME_FROG,
                    'create_at'    => date('Y-m-d H:i:s'),
                ]);
            }

            return $homeId;
        }
    }

    /**
     * 减掉余额
     *
     * @param $return
     *
     * @throws \think\Exception
     */
    public static function subBalance($return)
    {
        if (request()->isPost()) {
            $uid  = user('uid');
            $data = input('post.');

            $userInfo = Member::getMemberInfo($uid, true);
            if ($userInfo < $data['pay_total']) {
                throw new Exception('余额不足');
            }
            Member::addBalance($uid, $data['pay_total'], 2);
            Member::addBalanceRecord([
                'uid'         => $uid,
                'home_id'     => $return['home_id'],
                'order_no'    => $return['pay_order_no'],
                'amount'      => $data['pay_total'],
                'description' => '用户开房间支付',
                'prefix'      => 2,
                'create_time' => time(),
                'type'        => 6,
            ]);
            PayOrder::editStatus($return['pay_order_no'], PayOrder::STATUS_SUCCESS_PAY);
        }
    }

    /**
     * @param     $data
     * @param int $status
     *
     * @return int|string
     * @throws \think\Exception
     */
    public static function participateGame($data, $status = 0)
    {
        $uid      = user('uid');
        $homeId   = $data['home_id'];
        $homeInfo = GameHome::getHomeInfo($homeId, true);
        if ($homeInfo['game_home_parameter'] >= $homeInfo['game_home_number_people']) {
            throw new Exception('房间已满!');
        }
        $info = GameHome::getRecordInfo($homeId, $uid);
        if (!empty($info)) {
            throw new Exception('您已支付!');
        }
        if ($status == 1) {
            GameHome::addParameter($homeId);
        }

        return GameHome::add_game_record([
            'uid'          => $uid,
            'game_home_id' => $homeId,
            'pay_amount'   => $data['pay_total'],
            'pay_status'   => $status,
            'grade'        => 0,
            'type'         => 1,
            'game_key'     => GameHome::GAME_FROG,
            'create_at'    => date('Y-m-d H:i:s'),
        ]);
    }

    public static function setGameLottery($homeId)
    {
        Db::startTrans();
        try {
            $homeInfo = GameHome::getHomeInfo($homeId, true);
            if ($homeInfo['game_home_status'] !== 3) {
                //分配奖品
                $amount    = $homeInfo['game_home_parameter'] * $homeInfo['pay_amount'];
                $maxRecord = GameHome::get_home_max_record($homeId);
                $maxRecord = GameHome::confirmWin($homeId, $maxRecord['uid'], $maxRecord['grade']);

            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
        }

    }

    /**
     * 满房处理
     *
     * @param $homeId
     * @param $isTimeOut
     *
     * @return string
     */
    public static function homeIsFull($homeId, $isTimeOut)
    {
        Db::startTrans();
        try {
            $homeCompanyNum = GameHome::getCompanyNum($homeId);
            $homeInfo       = GameHome::getHomeInfo($homeId, true);
            if ($homeInfo['game_home_status'] === 3) {
                return '';
            }
            if ($isTimeOut || ($homeInfo['game_home_number_people'] == $homeCompanyNum)) {
                //已完成
                $getMax  = GameHome::getCompanyMax($homeId);
                $maxList = GameHome::getMaxList($homeId, $getMax['grade']);
                GameHome::getMaxWin($homeId, $getMax['grade']);
                $maxNum = count($maxList);
                //用户新增金豆
                $amount    = $homeInfo['game_home_number_people'] * $homeInfo['pay_amount'];
                $winAmount = round($amount / $maxNum, 2);
                GameHome::confirmWin($homeId, $getMax['uid'], $getMax['grade'], $maxNum, $winAmount);
                foreach ($maxList as $item) {
                    Member::addBalance($item['uid'], $winAmount);
                    Member::addBalanceRecord([
                        'uid'         => $item['uid'],
                        'amount'      => $winAmount,
                        'home_id'     => $homeId,
                        'description' => '手速对战胜利',
                        'prefix'      => 1,
                        'create_time' => time(),
                        'type'        => 7,
                    ]);
                }
            }
            Db::commit();
        } catch (Exception $e) {
            Db::rollback();
        }
    }

}

?>