<?php
/**
 * lottery
 *
 * @author    wangwei
 *
 */

namespace osc\mobile\controller;

use osc\admin\model\GameHome;
use think\Db;
use osc\admin\model\Home as HomeModel;
use think\Exception;

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

    /**
     * 新增记录
     */
    public function add_game_record()
    {
        $data = input('post.');
        GameHome::add_game_record(array(
            'uid' => UID,
            'grade' => $data['grade'],
            'type' => 0,
            'game_key' => GameHome::GAME_FROG,
            'create_at' => date('Y-m-d H:i:s'),
        ));
    }

    /**
     * 获取最高记录
     */
    public function get_max_record()
    {
        return GameHome::get_max_record(UID);
    }

    /**
     * 获取房间记录
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function get_home_record()
    {
        return GameHome::get_home_record(input('home_id'));
    }

    /**
     * 房间列表
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function home_list()
    {
        $where['game_home_status'] = array('<>', 0);
        $homeList = Db::name('game_home')
            ->where($where)
            ->order('game_home_status,create_at')
            ->select();
        $this->assign('home_list', $homeList);
    }

    /**
     * 判断是否满房
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function is_full()
    {
        $homeId = input('home_id');
        $homeInfo = HomeModel::getHomeInfo($homeId);
        if ($homeInfo['status'] > HomeModel::ADD_NUM) {
            $homeInfo['is_self'] = $homeInfo['lottery_uid'] == UID ? 1 : 0;
            return $homeInfo;
        }
        return array('status' => 0);
    }
}