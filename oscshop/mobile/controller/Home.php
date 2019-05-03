<?php
/**
 * lottery
 *
 * @author    wangwei
 *
 */

namespace osc\mobile\controller;

use think\Db;
use osc\admin\model\Home as HomeModel;

class Home extends MobileBase
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
     * 开设房间
     */
    public function set_up_home()
    {
        if (request()->isPost()) {

            $data = input('post.');
            //提现的额度
            if (empty($data['home_name'])) {
                return ['error' => '房间名不能为空'];
            }
            if (empty($data['password'])) {
                return ['error' => '密码不能为空'];
            }
            //负数
            if ($data['cash'] < 0) {
                return ['error' => '提现金额不能是负数'];
            }
            $data['create_at'] = date('Y-m-s H:i:s');
            $data['uid'] = UID;
            $homeId = Db::name('home')->insert($data, false, true);
            if ($homeId) {
                return ['success' => '开设成功', 'url' => '/mobile/goods/detail/home_id/' . $homeId];
            } else {
                return ['error' => '开设失败'];
            }

        }
    }

    /**
     * 房间列表
     */
    public function home_list($gid)
    {
        $homeList = Db::name('home')->where(['gid' => $gid])->order('status,create_at')->select();
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