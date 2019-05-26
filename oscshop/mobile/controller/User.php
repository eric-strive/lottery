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

use think\Db;
use osc\admin\model\Home;

class User extends MobileBase
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

        $this->assign('not_complete', $this->lottery_order_count(Home::NOT_COMPLETE));
        //        $this->assign('lottery', $this->lottery_order_count(Home::LOTTERY));
        $this->assign('not_get', $this->lottery_order_count(Home::NOT_GET));
        $this->assign('luck_not_get', $this->luck_order_count());
        //        $this->assign('complete', $this->lottery_order_count(Home::COMPLETE));
        $this->assign('userinfo', Db::name('member')->where('uid', UID)->find());
        $this->assign('SEO', ['title' => config('SITE_TITLE')]);

        $this->assign('flag', 'user');

        return $this->fetch();
    }

    function lottery_order_count($status)
    {
        if ($status == Home::NOT_GET) {
            return count(Db::name('home')->where(['status' => Home::LOTTERY, 'lottery_uid' => UID])->select());
        }

        return count(Home::HomeList($status, UID));
    }

    function luck_order_count()
    {
        return count(Db::name('luck_record')->where(['is_lottery' => 1, 'is_draw' => 0, 'uid' => UID])->select());
    }

    function wish_list()
    {

        $this->assign('top_title', '我的收藏');
        $this->assign('SEO', ['title' => '我的收藏-' . config('SITE_TITLE')]);

        return $this->fetch();
    }

    function ajax_wish_list()
    {

        $page = (int)input('param.page');//页码

        $limit = (6 * $page) . ",6";

        $list = Db::view('Goods', 'goods_id,image,price,name')
            ->view('MemberWishlist', 'uid', 'Goods.goods_id=MemberWishlist.goods_id')
            ->where(['MemberWishlist.uid' => UID])->limit($limit)->select();

        if (isset($list) && is_array($list)) {
            foreach ($list as $k => $v) {
                $list[$k]['image'] = resize($v['image'], 200, 200);
            }
        }

        return $list;

    }

    function remove_wish()
    {

        $goods_id = (int)input('param.id');
        Db::name('member_wishlist')->where(['uid' => UID, 'goods_id' => $goods_id])->delete();
        Db::name('member')->where('uid', UID)->setDec('wish', 1);
        storage_user_action(UID, user('nickname'), config('FRONTEND_USER'), '删除了收藏');

        $this->redirect('User/wish_list');
    }

    function get_qr_code()
    {

        $uid = user('uid');

        include_once EXTEND_PATH . 'phpqrcode/phpqrcode.php';

        $url = request()->domain() . '/mobile/index/agent_share/osc_aid/' . hashids()->encode($uid);

        $level = 3;

        $size = 4;

        $errorCorrectionLevel = intval($level);//容错级别

        $matrixPointSize = intval($size);//生成图片大小

        $path = DIR_IMAGE . 'images/qrcode/';

        $fileName = $uid . '.png';

        //判断文件是否存在，存在返回二维码图片名字
        $checkFile = $path . $fileName;

        if (!is_dir($path)) {
            @mkdir($path, 0777);
        }

        Db::name('wechat_share')->insert(['uid' => UID, 'type' => '推广商城二维码', 'url' => $url, 'create_time' => time()]);

        if (file_exists($checkFile)) {

            return '/public/uploads/images/qrcode/' . $fileName;
        }

        //生成二维码图片
        \QRcode::png($url, $path . $fileName, $errorCorrectionLevel, $matrixPointSize);

        return '/public/uploads/images/qrcode/' . $fileName;
    }

    /**
     * 用户充值
     */
    public function recharge()
    {
        if (in_wechat()) {
            $wechat = wechat();
            //调用微信收货地址接口，需要开通微信支付
            $this->assign('signPackage', $wechat->getJsSign(request()->url(true)));
        }
        $this->assign('top_title', '金豆充值');
        $this->assign('SEO', ['title' => '金豆充值-' . config('SITE_TITLE')]);

        return $this->fetch();
    }
}
