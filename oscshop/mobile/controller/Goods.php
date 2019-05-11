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

use osc\admin\model\duobaoRecord;
use osc\admin\model\Goods as GoodsModel;
use osc\admin\model\Home as HomeModel;
use osc\admin\model\LuckRecord;
use think\Db;

class Goods extends MobileBase
{

    //商品详情
    function detail()
    {
        cookie('jump_url', request()->url(true));
        $gid = (int)input('param.id');
        if (!$list = osc_goods()->get_goods_info($gid)) {
            $this->error('商品不存在！！');
        }
        $homeId = input('param.home_id');
        if ($homeId) {
            $this->assign('home_id', $homeId);
            $homeInfo = HomeModel::home_info_by_gid($list['goods']['goods_id'], $homeId, 1);
        } else {
            $homeInfo = HomeModel::home_info_by_gid($list['goods']['goods_id'], $list['goods']['periods']);
        }
        $homeList = HomeModel::getHomeListByGid($gid);
        if (empty($homeInfo)) {
            $this->error('该商品有误，请联系客服修正！');
        }
        if ($homeInfo['status'] != 0) {
            $this->assign('lottery_num', $homeInfo['lottery_num']);
        } else {
            $this->assign('lottery_num', '');
        }
        $this->assign('homeInfo', $homeInfo);
        $this->assign('percentage', duobaoRecord::get_periods($homeInfo));
        $this->assign('SEO', [
            'title' => $list['goods']['name'] . '-' . config('SITE_TITLE'),
            'keywords' => $list['goods']['meta_keyword'],
            'description' => $list['goods']['meta_description']
        ]);
        $duobaoList = duobaoRecord::getNumbers($homeInfo['id']);
        osc_goods()->update_goods_viewed((int)input('param.id'));
        $this->assign('duobaoList', $duobaoList);
        $this->assign('homeList', $homeList);
        $this->assign('top_title', $list['goods']['name']);
        $this->assign('goods', $list['goods']);
        $this->assign('image', $list['image']);
        $this->assign('options', $list['options']);
        $this->assign('discount', $list['discount']);
        $this->assign('mobile_description', $list['mobile_description']);

        //数据量大的时候，此处会有性能问题，视情况进行修改
        $this->assign('related_goods', Db::name('goods')->where('status',
            1)->field('goods_id,image,name')->order('viewed desc')->limit('6')->select());

        if (in_wechat()) {
            $this->assign('signPackage', wechat()->getJsSign(request()->url(true)));
        }

        return $this->fetch('detail');
    }

    //取得商品描述
    function get_description()
    {

        $this->assign('description', Db::name('goods_mobile_description_image')->where('goods_id',
            (int)input('param.id'))->order('sort_order asc')->select());

        exit($this->fetch());
    }

    function get_home_list()
    {
        $homeList = HomeModel::getHomeListByGid((int)input('param.id'));
        $this->assign('homeList', $homeList);
        exit($this->fetch());
    }

    //加入收藏
    function add_wish()
    {

        $goods_id = (int)input('post.id');

        if (!Db::name('goods')->where(array('goods_id' => $goods_id, 'status' => 1))->find()) {
            return ['error' => '产品不存在'];
        }

        $uid = user('uid');

        if (!$uid) {
            return ['error' => '请先登录'];
        }

        if (!Db::name('member_wishlist')->where(array('uid' => $uid, 'goods_id' => $goods_id))->find()) {
            Db::name('member_wishlist')->insert(array(
                'uid' => $uid, 'goods_id' => $goods_id, 'date_added' => date('Y-m-d H:i:s', time())
            ));
            Db::name('member')->where('uid', $uid)->setInc('wish', 1);
        }

        return ['success' => '收藏成功'];
    }

    function agent_share()
    {

        deal_agent_share();

        return $this->detail();
    }

    /**
     * 幸运购页面
     * @return mixed
     */
    function luck()
    {
        $gid = (int)input('param.id');
        if (!$list = osc_goods()->get_goods_info($gid)) {
            $this->error('商品不存在！！');
        }
        if (in_wechat()) {
            $wechat = wechat();
            //调用微信收货地址接口，需要开通微信支付
            $this->assign('signPackage', $wechat->getJsSign(request()->url(true)));
            session('jssdk_order', null);
        }
        $list['goods']['image'] = resize($list['goods']['image'], 80, 80);
        $this->assign('goods', $list['goods']);
        return $this->fetch('luck');
    }

    /**
     * 抽奖成功
     * @return mixed
     */
    function luck_success()
    {
        $isLottery = false;
        $gid = (int)input('id');
        $order_no = (int)input('order_no');
        $uid = user('uid');
        if (empty($gid)) {
            $this->error('商品不存在！！');
        }
        if (empty($uid)) {
            $this->error('请先登录！！', url('login/login'));
        }
        $goodsInfo = GoodsModel::getGoodsInfo($gid);
        $recordSum = LuckRecord::recordSum($gid, $uid);
        if ($recordSum >= $goodsInfo['lotter_price']) {
            //中奖了
            LuckRecord::setLottery($order_no);
            $isLottery = true;
        }
        $this->assign('goodsInfo', $goodsInfo);
        $this->assign('isLottery', $isLottery);
        return $this->fetch('luck_success');
    }


}

?>