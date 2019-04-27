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

class duobaoRecord
{

    public function validate($data)
    {

        $error = array();
        if (empty($data['dduonum'])) {
            $error['error'] = '号码必填';
        }
        if ($error) {
            return $error;
        }

    }

    /**
     * 获取商品购买的百分比
     * @param $periods
     * @param $gid
     * @param int $type
     * @return int|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function get_duobao_num($periods, $gid, $type = 0)
    {
        $query = Db::name('home')
            ->where('type', $type)
            ->where('gid', $gid);
        if ($type == 1) {
            $query->where('home_num', $periods);
        } else {
            $query->where('goods_periods', $periods);
        }
        $date = $query->find();

        if (empty($date)) {
            return '0%';
        }
        return sprintf("%01.0f", $date['goods_buy_num'] / $date['lottery_drifts'] * 100) . '%';
    }
}

?>