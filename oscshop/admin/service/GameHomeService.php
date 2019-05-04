<?php

namespace osc\admin\service;

use osc\admin\model\GameHome;
use osc\admin\model\Member;
use think\Db;
use think\Exception;

//游戏房间
class GameHomeService
{
    function homeIsFull($homeId)
    {
        $homeCompanyNum = GameHome::getCompanyNum($homeId);
        $homeInfo = GameHome::getHomeInfo($homeId, true);
        if ($homeInfo['game_home_number_people'] == $homeCompanyNum) {
            //已完成
            $getMax = GameHome::getCompanyMax($homeId);
            GameHome::confirmWin($homeId, $getMax['uid']);
            //用户新增金豆
            $amount = $homeInfo['game_home_number_people'] * $homeInfo['pay_amount'];
            Member::addBalance($getMax['uid'], $amount);
            Member::addBalanceRecord(array(
                'uid' => $getMax['uid'],
                'amount' => $homeId,
                'home_id' => $amount,
                'description' => '用户玩游戏获取',
                'prefix' => 1,
                'create_time' => time(),
                'type' => 5,
            ));
        }
    }

    /**
     * 开设房间
     */
    public function set_game_home()
    {
        if (request()->isPost()) {

            $data = input('post.');
            //提现的额度
            if (empty($data['game_home_name'])) {
                throw new Exception('房间名不能为空');
            }
//            if (empty($data['game_home_password'])) {
//                return ['error' => '密码不能为空'];
//            }
            //负数
            if ($data['pay_amount'] < 0) {
                throw new Exception('金额不能是负数');
            }
            if ($data['game_home_number_people'] < 2) {
                throw new Exception('至少两人');
            }
            $data['create_at'] = date('Y-m-s H:i:s');
            $data['game_home_uid'] = UID;
            $homeId = GameHome::add_game_home($data);
            if ($homeId) {
                return ['success' => '开设成功', 'url' => '/mobile/goods/detail/home_id/' . $homeId];
            } else {
                throw new Exception('开设失败');
            }
        }
    }
}

?>