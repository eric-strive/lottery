<?php
/**
 * lottery
 *
 * @author    wangwei
 *
 */

namespace osc\mobile\controller;

use osc\admin\model\GameHome;
use osc\admin\model\Member;
use think\Db;

class Game extends MobileBase
{
    protected function _initialize()
    {
        parent::_initialize();
        define('UID', osc_service('mobile', 'user')->is_login());
        //手机版
        if (!UID) {
            if (!in_wechat()) {
                $this->redirect('login/login');
            } else {
                $this->error('系统错误');
            }
        }
    }

    public function index()
    {
        if (in_wechat()) {
            $wechat = wechat();
            //调用微信收货地址接口，需要开通微信支付
            $this->assign('signPackage', $wechat->getJsSign(request()->url(true)));
            session('jssdk_order', null);
        }
        if (input('param.home_id', 0)) {
            $homeInfo = null;
            if (input('param.home_id', 0) == 'house-owner') {
                $homeInfo = GameHome::getLastHomeInfoByUid(UID);
            }

            return $this->entering_room($homeInfo);
        }

        return $this->fetch();
    }

    public function entering_room($homeInfo = null)
    {
        if (empty($homeInfo)) {
            $home_id = (int)input('param.home_id');
            //        $sign = (int)input('param.sign');
            $homeInfo = GameHome::getHomeInfo($home_id);
        }
        if (empty($homeInfo)) {
            $this->error('您没有访问权限');
        }
        if ($homeInfo['game_home_status'] == 3) {
            $this->error('游戏已结束!');
        }
        if (!empty($homeInfo['start_at']) && (strtotime($homeInfo['start_at']) + 50) < time()) {
            GameHome::
            $this->error('活动还未开始或已结束');
        }
        $homeUserInfo = Member::getMemberInfo($homeInfo['game_home_uid']);

        $record = GameHome::get_home_record($homeInfo['game_home_id']);
        $this->assign('homeUserInfo', $homeUserInfo);
        $this->assign('uid', UID);
        $this->assign('record', $record);
        $this->assign('homeInfo', $homeInfo);
        $this->assign('SEO', ['title' => '房间-' . config('SITE_TITLE')]);
        $this->assign('top_title', '房间');

        return $this->fetch('entering_room');
    }

    /**
     * 新增记录
     */
    public function add_game_record()
    {
        $data = input('post.');
        GameHome::add_game_record([
            'uid'       => UID,
            'grade'     => $data['grade'],
            'type'      => 0,
            'game_key'  => GameHome::GAME_FROG,
            'create_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * 获取最高记录
     */
    public function get_max_record()
    {
        return GameHome::get_max_record(UID);
    }

    /**
     * 房间列表
     *
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function get_home_list()
    {
        $where['game_home_status'] = ['<>', 0];
        $homeList                  = Db::name('game_home')
            ->where($where)
            ->order('game_home_status,create_at')
            ->select();
        $this->assign('homeList', $homeList);

        return $this->fetch();
    }

    /**
     * 判断是否满房
     *
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @throws \think\exception\PDOException
     *
     */
    public function is_full()
    {
        $homeId             = input('home_id');
        $homeInfo           = GameHome::getHomeInfo($homeId);
        $remain             = $homeInfo['game_home_number_people'] - $homeInfo['game_home_parameter'];
        $homeInfo['remain'] = $remain;
        $residueTime        = 0;//剩余开奖时间，默认是10秒
        //是否满房
        if ($remain > 0) {
            return ['status' => 0, 'remain' => $remain, 'residueTime' => $residueTime];
        }

        //是否已完成
        if ($homeInfo['game_home_status'] == 3) {
            $userInfo             = Member::getMemberInfo($homeInfo['game_home_win_uid']);
            $homeInfo['nickname'] = $userInfo['nickname'];
            $homeInfo['userpic']  = $userInfo['userpic'];
            $homeInfo['is_self']  = $homeInfo['game_home_win_uid'] == UID ? 1 : 0;

            return $homeInfo;
        }

        //是否已经全部准备
        if ($homeInfo['game_home_status'] == 2) {
            $residueTime             = strtotime($homeInfo['start_at']) + 10 - time();
            $homeInfo['residueTime'] = $residueTime;

            return $homeInfo;
        }

        //没进入就设置准备
        $prepareNum = GameHome::getPrepareNum($homeId);
        if ($prepareNum >= $homeInfo['game_home_number_people']) {
            $homeInfo['residueTime'] = 10;
            GameHome::setStartTime($homeId);
        }

        return $homeInfo;
    }

    /**
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function get_home_record()
    {
        $homeId = input('home_id');
        $record = GameHome::get_home_record($homeId);

        return $record;
    }

    public function start_game()
    {
        if (in_wechat()) {
            $wechat = wechat();
            //调用微信收货地址接口，需要开通微信支付
            $this->assign('signPackage', $wechat->getJsSign(request()->url(true)));
            session('jssdk_order', null);
        }
        $homeId     = input('home_id');
        $userId     = UID;
        $homeInfo   = GameHome::getHomeInfo($homeId);
        $recordInfo = GameHome::getRecordInfo($homeId, $userId);
        if (empty($recordInfo) || $recordInfo['pay_status'] != 1 || $recordInfo['is_affirm'] != 1) {
            $this->error('您无权访问');
        }
        if (empty($homeInfo['start_at']) || (strtotime($homeInfo['start_at']) + 50) < time()) {
            $this->error('游戏已结束');
        }
        $this->fetch();
    }

    public function game_prepare()
    {
        $record_id = input('record_id');
        if (empty($record_id)) {
            return ['error' => '数据出错'];
        }
        $s = GameHome::prepare($record_id, UID);
        if (empty($s)) {
            return ['error' => '数据出错!'];
        }
    }
}