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

namespace osc\mobile\controller;

use osc\admin\model\LuckRecord;
use osc\admin\model\Home as HomeModel;
use think\Db;

class LotteryOrder extends MobileBase
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

    function index()
    {
        $status = input('status') !== null ? (int)input('param.status', '') : '';
        $this->assign('status', $status);
        $this->assign('top_title', '我的订单');
        $this->assign('SEO', ['title' => '我的订单-' . config('SITE_TITLE')]);
        return $this->fetch();
    }

    function ajax_order_list()
    {
        $page = (int)input('param.page');//页码
        $status = input('status') !== null ? (int)input('param.status', '') : '';
        //开始数字,数据量
        $limit = (8 * $page) . ",8";
        if ($status === '') {
            $orders = HomeModel::HomeList(null, $limit);
        } elseif ($status === HomeModel::NOT_GET) {
            $orders = Db::name('home')->where(array('status' => HomeModel::LOTTERY, 'lottery_uid' => 2))->select();
        } else {
            $orders = HomeModel::HomeList($status, $limit);
        }
        $this->assign('order', $orders);
        $this->assign('uid', 2);
        exit($this->fetch());

    }

    function order_info()
    {
        if (!$order = osc_order()->order_info(input('param.order_id'), UID)) {
            $this->error('非法操作！！');
        }

        $this->assign('order', $order);
        $this->assign('SEO', ['title' => '订单详情-' . config('SITE_TITLE')]);
        $this->assign('top_title', '订单详情');
        return $this->fetch();
    }

    function cancel_order()
    {
        osc_order()->cancel_order((int)input('param.order_id'), UID);
        storage_user_action(UID, user('nickname'), config('FRONTEND_USER'), '取消了订单');
        return 1;
    }

    public function luck_list()
    {
        $lickList = LuckRecord::getRecord(UID);
        $lotteryList = LuckRecord::getRecord(UID, LuckRecord::LOTTERY);
        $this->assign('luck_list', $lickList);
        $this->assign('lotteryList', $lotteryList);
        $this->assign('SEO', ['title' => '幸运购记录-' . config('SITE_TITLE')]);
        $this->assign('top_title', '幸运购记录');
        return $this->fetch();
    }

    public static function confirm_get()
    {
        $luck_record_id = input('luck_record_id');
        $home_id = input('home_id');
        if ($home_id) {
            HomeModel::confirmGet($home_id, 2);
        } else {
            LuckRecord::setDraw($luck_record_id, UID);
        }
    }
}
