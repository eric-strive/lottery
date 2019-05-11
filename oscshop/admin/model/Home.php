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
use think\Exception;
use think\exception\ErrorException;

class Home
{

    const ADD_NUM = 0;
    const REDUCE_NUM = 1;

    const NOT_COMPLETE = 0;//未完成
    const LOTTERY = 1;//已开奖
    const COMPLETE = 2;//完成
    const NOT_GET = 3;//未领取

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
        $home_name = isset($data['home_name']) ? $data['home_name'] : 0;
        $password = isset($data['password']) ? $data['password'] : 0;
        $periods = isset($data['periods']) ? $data['periods'] : 0;
        $gid = $data['gid'];
        $uid = isset($data['uid']) ? $data['uid'] : 0;
        $type = 0;
        $home_num_id = 0;
        $goodInfo = Goods::getGoodsInfo($gid);
        if (!empty($home_name) && !empty($password)) {
            $getUserHome = self::getUserHome($uid, $gid);
            if (!empty($getUserHome)) {
                return $getUserHome;
            }
            $home_num_id = self::getGidHomeNum($gid);
            $type = 1;
        } else {
            $periods = $periods + 1;
            Goods::setPeriods($gid, $periods);
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
            'sign' => build_order_no(),
        ];
        $insertId = Db::name('home')->insert($homeInfo, false, true);
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

    /**
     * 判断用户是否有私开房间
     * @param $uid
     * @param $gid
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getUserHome($uid, $gid)
    {
        $info = Db::name('home')
            ->where(array(
                'gid' => $gid,
                'uid' => $uid,
                'status' => self::NOT_COMPLETE,
            ))
            ->find();
        if (!empty($info)) {
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

    /**
     * 判断金额是否正确
     * @param $gid
     * @param $payTotal
     * @param $goodsNum
     * @return bool
     */
    public static function buyGoodsCheck($gid, $payTotal, $goodsNum)
    {
        $goodsOneAmount = Db::name('goods')
            ->where('goods_id=' . $gid)
            ->value('doubao_price');
        if (intval($payTotal) != intval($goodsNum * $goodsOneAmount)) {
            return false;
        }
        return true;
    }

    /**
     * 新增或减少份额
     * @param $homeId
     * @param $goodsNum
     * @param int $changeType
     * @return int|true
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function changePortion($homeId, $goodsNum, $changeType = self::ADD_NUM)
    {
        $homeInfo = Db::name('home')->where('id=' . $homeId)->lock(true)->find();
        if ($changeType == self::ADD_NUM) {
            $remain = $homeInfo['lottery_drifts'] - $homeInfo['goods_buy_num'];
            if (intval($remain) < intval($goodsNum)) {
                throw new Exception('商品剩余份额不足！');
            }
            return Db::name('home')->where('id=' . $homeId)->setInc('goods_buy_num', $goodsNum);
        } else {
            if (intval($homeInfo['goods_buy_num']) < intval($goodsNum)) {
                throw new Exception('处理份额出错！');
            }
            return Db::name('home')->where('id=' . $homeId)->setDec('goods_buy_num', $goodsNum);
        }
    }

    /**
     * 获取私房信息
     * @param $homeId
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getHomeInfo($homeId, $isLock = false)
    {
        return Db::name('home')
            ->where('id', $homeId)
            ->lock($isLock)
            ->find();
    }

    /**
     * 满房
     * @param $homeId
     * @param $uid
     * @param $lottery_num
     * @param $rand_num
     * @param $lottery_timestamp
     * @return int|string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function homeFull($homeId, $uid, $lottery_num, $rand_num, $lottery_timestamp)
    {
        $return = Db::name('home')
            ->where('id', $homeId)
            ->update(array(
                'lottery_uid' => $uid,
                'lottery_num' => $lottery_num,
                'lottery_rand' => $rand_num,
                'lottery_timestamp' => $lottery_timestamp,
                'lottery_at' => date('Y-m-d H:i:s'),
                'status' => 1,
            ));
        return $return;
    }

    /**
     * @param $gid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getHomeListByGid($gid)
    {
        return Db::name('home')
            ->where(array(
                'home_num' => array('<>', 0),
                'gid' => $gid,
                'status' => self::NOT_COMPLETE
            ))->select();
    }

    public static function getHomeList()
    {
        return Db::view('Home', '*')
            ->view('Goods', 'name', 'Home.gid=Goods.goods_id')
            ->view('Member', 'nickname', 'Member.uid=Home.lottery_uid', 'left')
            ->order('Home.status asc Home.id asc')
            ->paginate(20, false);
    }

    /**
     * @param $uid
     * @param int $status
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getHomeListByUid($uid, $status = self::NOT_COMPLETE)
    {
        return Db::name('home')
            ->where(array(
                'uid' => $uid,
                'status' => $status
            ))->select();
    }

    public static function HomeList($status = null, $uid, $limit = null)
    {
        $where['dr.uid'] = $uid;
        if ($status !== null) {
            $where['h.status'] = $status;
        }
        return Db::name('home')
            ->alias('h')
            ->join('duobao_record dr', 'h.id=dr.home_id')
            ->where($where)
            ->group('dr.home_id')
            ->limit($limit)
            ->select();
    }

    /**
     * @param $id
     * @param $uid
     * @return int|string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function confirmGet($id, $uid)
    {
        $confirm = Db::name('home')
            ->where(array(
                'id' => $id,
                'lottery_uid' => $uid,
            ))
            ->update(array(
                'status' => self::COMPLETE,
                'confirm_at' => date('Y-m-d H:i:s'),
            ));
        if ($confirm) {
            //送金豆给用户
            $homeInfo = self::getHomeInfo($id);
            Member::giveBalanceLuck($homeInfo);
        }
    }
    public static function home_info_by_sign($home_id)
    {
        return Db::name('home')
            ->where('id', $home_id)
//            ->where('sign', $sign)
            ->find();
    }
}

?>