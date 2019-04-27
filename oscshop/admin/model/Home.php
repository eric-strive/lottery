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

namespace osc\admin\model;

use think\Db;
use think\exception\ErrorException;

class Home
{

    public function validate($data)
    {

        $error = array();
        if (empty($data['name'])) {
            $error['error'] = '产品名称必填';
        } elseif (!isset($data['goods_category'])) {
            $error['error'] = '产品分类必填';
        }

        if (isset($data['goods_option'])) {
            foreach ($data['goods_option'] as $goods_option) {

                if (!isset($goods_option['goods_option_value'])) {
                    $error['error'] = '选项值必填';
                }

                foreach ($goods_option['goods_option_value'] as $k => $v) {
                    if ((int)$v['quantity'] <= 0) {
                        $error['error'] = '选项数量必填';
                    }
                }
            }
        }

        if ($error) {
            return $error;
        }

    }

    public function get_home_list($params = array())
    {
        $where = array();
        if (isset($params['uid']) && !empty($params['uid'])) {
            $where['g.uid'] = $params['uid'];
        }
        if (isset($params['gid']) && !empty($params['gid'])) {
            $where['g.gid'] = $params['gid'];
        }
        if (isset($params['type']) && !empty($params['type'])) {
            $where['g.type'] = $params['type'];
        }
        return Db::name('home')->where($where)
            ->alias('h')
            ->field('h.* , g.name,g.price as goods_price')
            ->join(config('database.prefix') . 'goods g', 'g.goods_id = h.gid')
            ->order('status ASC,create_at desc')
            ->paginate();
    }

    public static function add_home($data)
    {
        $home_name = $data['home_name'] ?? 0;
        $password = $data['password'] ?? 0;
        $periods = $data['periods'] ?? 0;
        $gid = $data['gid'];
        $uid = $data['uid'] ?? 0;
        $type = 0;
        $home_num_id = 0;
        $goodInfo = Goods::get_goods_info($gid);
        if (!empty($home_name) && !empty($password)) {
            $getUserHome = self::getUserHome($uid, $gid);
            if (!empty($getUserHome)) {
                return $getUserHome;
            }
            $home_num_id = self::getGidHomeNum($gid);
            $type = 1;
        } else {
            $periods = $periods + 1;
        }
        $homeInfo = [
            'uid' => $uid,
            'gid' => $gid,
            'goods_price' => $goodInfo['price'],
            'lottery_drifts' => $goodInfo['price'] / $goodInfo['doubao_price'],
            'goods_periods' => $periods,
            'home_num' => $home_num_id,
            'home_name' => $home_name,
            'password' => $password,
            'type' => $type,
        ];
        $insertId = Db::name('home')->insert($homeInfo, false, true);
        Goods::setPeriods($gid, $periods);
        return ['home_id' => $insertId];
    }

    public static function getGidHomeNum($gid)
    {
        $home_num_id = Db::name('home')
            ->where('gid', $gid)
            ->order('id desc')->limit(1)
            ->value('home_num');
        return $home_num_id ? $home_num_id + 1 : 1;
    }

    public static function getUserHome($uid, $gid)
    {
        $info = Db::name('home')
            ->where('gid', $gid)
            ->where('uid', $uid)
            ->find();
        if (!empty($info) && $info['status'] == 0) {
            return ['error' => '该商品您已经开过一个，请先投满！'];
        }
    }

    public static function home_info_by_gid($gid, $goods_periods, $type = 0)
    {
        if ($type) {
            $d = Db::name('home')
                ->where('id', $goods_periods)
                ->find();
            if (empty($d)) {
                throw new ErrorException('房间不存在');
            }
            return $d;
        } else {
            return Db::name('home')
                ->where('gid', $gid)
                ->where('goods_periods', $goods_periods)
                ->find();
        }
    }
}

?>