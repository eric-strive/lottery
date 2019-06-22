<?php

namespace osc\member\controller;

use osc\admin\model\duobaoRecord;
use osc\admin\model\GameHome;
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
        $this->assign('list', Home::getHomeList(true));
        $this->assign('title', '私人房管理');
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        return $this->fetch();

    }

    //游戏房间列表
    public function game_home_list()
    {
        $this->assign('list', GameHome::getHomeRecordList());
        $this->assign('title', '私人房管理');
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        return $this->fetch();

    }

    //更新游戏分数
    function update_game_grade()
    {
        $data = input('post.');
        GameHome::update_game_grade([
            'game_record_id' => $data['game_record_id'],
        ], $data['grade']);

        return true;
    }

    //房间列表
    public function home_deal()
    {
        $this->assign('list', Home::getHomeList(true));
        $this->assign('title', '异常处理');
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        return $this->fetch();

    }

    /**
     * 错误房间处理
     *
     * @return bool
     */
    function home_error_deal()
    {
        $data   = input('post.');
        $homeId = $data['home_id'];
        $maxNum = duobaoRecord::getLastNum($homeId);
        if ($maxNum > 0) {
            Home::editNum($homeId, $maxNum);
        }

        return true;
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

    public function account_info()
    {
        $startDate = date('Y-m-d');
        $endDate   = date('Y-m-d', time() + 86400);
        //微信价值
        $payTotal        = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 1)
            ->where('status', '=', 1)
            ->sum('pay_amount');
        $homePayTotal    = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 1)
            ->where('order_type', '=', 1)
            ->where('status', '=', 1)
            ->sum('pay_amount');
        $luckPayTotal    = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 1)
            ->where('order_type', '=', 2)
            ->where('status', '=', 1)
            ->sum('pay_amount');
        $balancePayTotal = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 1)
            ->where('order_type', '=', 3)
            ->where('status', '=', 1)
            ->sum('pay_amount');
        $gamePayTotal    = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 1)
            ->where('order_type', '=', 4)
            ->where('status', '=', 1)
            ->sum('pay_amount');
        //花费的金豆
        $homeJindouTotal = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 2)
            ->where('order_type', '=', 1)
            ->where('status', '=', 1)
            ->sum('pay_amount');
        $luckJindouTotal = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 2)
            ->where('order_type', '=', 2)
            ->where('status', '=', 1)
            ->sum('pay_amount');
        $gameJindouTotal = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 2)
            ->where('order_type', 'in', '4,5')
            ->where('status', '=', 1)
            ->sum('pay_amount');
        $jindouTotal     = Db::name('pay_order')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('pay_type', '=', 2)
            ->where('status', '=', 1)
            ->sum('pay_amount');

        //总领取商品价值
        $homeTotal     = Db::name('home')
            ->where('lottery_at', '>=', $startDate)
            ->where('lottery_at', '<', $endDate)
            ->where('status', '=', 2)
            ->sum('goods_price');
        $luckTotal     = Db::view('LuckRecord')
            ->view('Goods', 'price', 'LuckRecord.gid=Goods.goods_id')
            ->where('create_at', '>=', $startDate)
            ->where('create_at', '<', $endDate)
            ->where('LuckRecord.is_draw', '=', 1)
            ->sum('price');
        $receiverTotal = $homeTotal + $luckTotal;
        //平台的金豆总数
        $balanceTotal = Db::name('member')->sum('balance');

        //用户花费的金豆
        $spendBalanceTotal = Db::name('balance')
            ->where('create_time', '>=', strtotime($startDate))
            ->where('create_time', '<', strtotime($endDate))
            ->where('prefix', '=', 2)
            ->sum('amount');
        $addBalanceTotal   = Db::name('balance')
            ->where('create_time', '>=', strtotime($startDate))
            ->where('create_time', '<', strtotime($endDate))
            ->where('prefix', '=', 1)
            ->sum('amount');

        $this->assign('startDate', $startDate);
        $this->assign('payTotal', $payTotal);
        $this->assign('homePayTotal', $homePayTotal);
        $this->assign('luckPayTotal', $luckPayTotal);
        $this->assign('gamePayTotal', $gamePayTotal);
        $this->assign('balancePayTotal', $balancePayTotal);
        $this->assign('payTotal', $payTotal);

        $this->assign('jindouTotal', $jindouTotal);
        $this->assign('homeJindouTotal', $homeJindouTotal);
        $this->assign('luckJindouTotal', $luckJindouTotal);
        $this->assign('gameJindouTotal', $gameJindouTotal);

        $this->assign('receiverTotal', $receiverTotal);
        $this->assign('homeTotal', $homeTotal);
        $this->assign('luckTotal', $luckTotal);

        $this->assign('balanceTotal', $balanceTotal);
        $this->assign('spendBalanceTotal', $spendBalanceTotal);
        $this->assign('addBalanceTotal', $addBalanceTotal);

        $this->assign('title', '系统对账');

        return $this->fetch();

        dump('时间：' . $startDate);
        dump('微信总收款：' . $payTatol . '元');
        dump('花费的金豆总额：' . $jindouTatol . '元');
        dump('领取的总价值：' . $receiverTatol . '元');
        dump('用户总余额：' . $balanceTatol . '元');
        exit;
    }
}

?>