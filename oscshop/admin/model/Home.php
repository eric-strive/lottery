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
}

?>