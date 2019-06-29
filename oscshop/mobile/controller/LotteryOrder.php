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
use osc\admin\model\Member;
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
        $this->assign('all', 0);

        return $this->fetch();
    }

    function index_all()
    {
        $status = input('status') !== null ? (int)input('param.status', '') : '';
        $this->assign('status', $status);
        $this->assign('top_title', '我的订单');
        $this->assign('all', 1);
        $this->assign('SEO', ['title' => '我的订单-' . config('SITE_TITLE')]);

        return $this->fetch('index');
    }

    function index_total()
    {
        $status = input('status') !== null ? (int)input('param.status', '') : '';
        $this->assign('status', $status);
        $this->assign('top_title', '我的订单');
        $this->assign('all', 2);
        $this->assign('SEO', ['title' => '我的订单-' . config('SITE_TITLE')]);

        return $this->fetch('index');
    }

    function ajax_order_list()
    {
        $page   = (int)input('param.page');//页码
        $all    = (int)input('param.all');//页码
        $status = input('status') !== null ? (int)input('param.status', '') : '';
        //开始数字,数据量
        $limit = (8 * $page) . ",8";
        if ($status === '') {
            $orders = HomeModel::HomeList(null, UID, $limit);
        } elseif ($status === 2) {
            $orders = HomeModel::HomeList('2,3', UID, 10);
        } elseif ($status === HomeModel::NOT_GET) {
            $showTime = time() - 45;
            if ($all === 1) {
                $where = ['h.status' => ['>=', HomeModel::LOTTERY], 'h.lottery_timestamp' => ['<', $showTime],];
            } elseif ($all === 2) {
                $where = ['h.status' => HomeModel::LOTTERY, 'h.lottery_timestamp' => ['<', $showTime],];
            } else {
                $where = [
                    'h.status'            => HomeModel::LOTTERY,
                    'h.lottery_timestamp' => ['<', $showTime],
                    'lottery_uid'         => UID,
                ];
            }
            $orders = Db::name('home')
                ->alias('h')
                ->field('h.*,m.*,gi.image,g.return_venosa')
                ->join('goods g', 'h.gid=g.goods_id')
                ->join('goods_image gi', 'h.gid=gi.goods_id')
                ->join('member m', 'h.lottery_uid=m.uid')
                ->where($where)
                ->order('h.status asc')
                ->select();
        } else {
            $orders = HomeModel::HomeList($status, UID, $limit);
        }
        foreach ($orders as $k => $v) {
            $orders[$k]['image'] = resize($v['image'], 230, 230);
        }
        $this->assign('all', $all);
        $this->assign('order', $orders);
        $this->assign('uid', UID);
        exit($this->fetch());

    }

    public function luck_list()
    {
        //        $lickList = LuckRecord::getRecord(UID);
        $lotteryList = LuckRecord::getRecord(UID, LuckRecord::LOTTERY, 0);
        foreach ($lotteryList as $k => $v) {
            $lotteryList[$k]['image'] = resize($v['image'], 230, 230);
        }
        //        $this->assign('luck_list', $lickList);
        $this->assign('lotteryList', $lotteryList);
        $this->assign('SEO', ['title' => '幸运购记录-' . config('SITE_TITLE')]);
        $this->assign('top_title', '幸运购记录');

        return $this->fetch();
    }

    public function luck_all_list()
    {
        $lotteryList = LuckRecord::getRecord(true, LuckRecord::LOTTERY, 0);
        foreach ($lotteryList as $k => $v) {
            $lotteryList[$k]['image'] = resize($v['image'], 230, 230);
        }
        //        $this->assign('luck_list', $lickList);
        $this->assign('lotteryList', $lotteryList);
        $this->assign('SEO', ['title' => '幸运购记录-' . config('SITE_TITLE')]);
        $this->assign('top_title', '幸运购确认');

        return $this->fetch();
    }

    public static function confirm_get()
    {
        $luck_record_id = input('luck_record_id');
        $return_venosa  = input('return_venosa', 0);
        $home_id        = input('home_id');
        if ($home_id) {
            $homeInfo = HomeModel::getHomeInfo($home_id, true);
            if ($homeInfo['status'] !== HomeModel::COMPLETE) {
                HomeModel::confirmGet($home_id);
                Member::giveBalanceLuck($homeInfo['lottery_uid'], $return_venosa);
            }
        } else {
            $luckInfo = Db::name('luck_record')
                ->where(['luck_record_id' => $luck_record_id])
                ->find();
            if ($luckInfo['is_draw'] !== 1) {
                LuckRecord::setDraw($luck_record_id);
                Member::giveBalanceLuck($luckInfo['uid'], $return_venosa);
            }
        }
    }

    public function get_bug_record()
    {
        $bugList = Home::getBugRecord(null);
        $this->assign('bugList', $bugList);
        $this->assign('SEO', ['title' => '购买记录-' . config('SITE_TITLE')]);
        $this->assign('top_title', '购买记录');

        return $this->fetch();
    }
}
