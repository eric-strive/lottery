<?php

namespace osc\member\controller;

use osc\admin\model\Home as HomeModel;
use osc\admin\model\LuckRecord;
use think\Controller;
use think\Db;
use osc\admin\model\Home;

class Goods extends controller
{

    protected function _initialize()
    {
        parent::_initialize();
        $this->assign('breadcrumb1', '商品');
        $this->assign('breadcrumb2', '商品管理');
    }

    //商品列表
    public function index()
    {
        $filter = input('param.');

        if (isset($filter['type']) && $filter['type'] == 'search') {
            $list = osc_goods()->get_category_goods_list($filter, config('page_num'));
        } else {
            $list = osc_goods()->get_goods_list($filter, config('page_num'));
        }
        $this->assign('title', '幸运购管理');
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        $this->assign('category', osc_goods()->get_category_tree());

        $this->assign('list', $list);

        return $this->fetch();

    }

    //房间列表
    public function home_list()
    {
        $this->assign('list', Home::getHomeList());
        $this->assign('title', '私人房管理');
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        return $this->fetch();

    }

    function update_luck()
    {
        $data = input('post.');

        $update['goods_id']     = (int)$data['goods_id'];
        $update['lotter_price'] = (float)$data['lotter_price'];

        if (Db::name('goods')->update($update)) {
            storage_user_action(1, session('user_auth.username'), config('BACKEND_USER'), '手机更新幸运购');

            return true;
        }
    }

    function update_lottery_num()
    {
        $data = input('post.');

        $update['lottery_num'] = (int)$data['lottery_num'];
        $update['id']          = (float)$data['home_id'];

        if (Db::name('home')->update($update)) {
            storage_user_action(1, session('user_auth.username'), config('BACKEND_USER'), '手机更新幸运购');

            return true;
        }
    }

    public function home_confirm()
    {
        $this->assign('list', Home::getHomeConfirmList());
        $this->assign('title', '送金豆');
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        return $this->fetch();
    }

    function return_venosa()
    {
        $data = input('post.');

        $return_venosa = (int)$data['return_venosa'];
        $home_id       = $data['home_id'];

        Home::retrunJindou($home_id, $return_venosa);
        storage_user_action(1, session('user_auth.username'), config('BACKEND_USER'), '手机更新幸运购');

        return true;
    }

    /**
     * 幸运购确认领取
     *
     * @return mixed
     */
    public function luck_list()
    {
        $this->assign('list', LuckRecord::getRecord(null, 1));
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');
        $this->assign('title', '幸运购确认领取');

        return $this->fetch();
    }

    public function confirm_get()
    {
        $luck_record_id = input('luck_record_id');
        $home_id        = input('home_id');
        if ($home_id) {
            HomeModel::confirmGet($home_id);
        } else {
            LuckRecord::setDraw($luck_record_id);
        }
    }
}

?>