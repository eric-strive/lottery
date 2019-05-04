<?php

namespace osc\admin\model;

use think\Db;
use think\Exception;
use think\exception\ErrorException;

class GameHome
{

    const ADD_NUM = 0;
    const REDUCE_NUM = 1;

    const NOT_COMPLETE = 0;//未完成
    const LOTTERY = 1;//已开奖
    const COMPLETE = 2;//完成
    const NOT_GET = 3;//未领取

    const GAME_FROG = 'frog';

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

    public static function add_game_home($homeInfo)
    {
        return Db::name('game_home')
            ->insert($homeInfo, false, true);
    }

    public static function add_game_record($gameRecord)
    {
        return Db::name('game_record')
            ->insert($gameRecord, false, true);
    }

    /**
     * 新增或减少份额
     * @param $homeId
     * @param int $changeType
     * @return int|true
     * @throws Exception
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function changePortion($homeId, $changeType = self::ADD_NUM)
    {
        $homeInfo = Db::name('game_home')->where('game_home_id=' . $homeId)->lock(true)->find();
        if ($changeType == self::ADD_NUM) {
            if (intval($homeInfo['game_home_number_people']) <= intval($homeInfo['game_home_parameter'])) {
                throw new Exception('房间已满！');
            }
            return Db::name('game_home')->where('game_home_id=' . $homeId)->setInc('game_home_parameter', 1);
        } else {
            if (intval($homeInfo['game_home_parameter']) == 0) {
                throw new Exception('处理份额出错！');
            }
            return Db::name('game_home')->where('game_home_id=' . $homeId)->setDec('game_home_parameter', 1);
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
        return Db::name('game_home')
            ->where('game_home_id', $homeId)
            ->lock($isLock)
            ->find();
    }

    public static function getHomeList()
    {
        return Db::view('Home', '*')
            ->view('Goods', 'name', 'Home.gid=Goods.goods_id')
            ->view('Member', 'nickname', 'Member.uid=Home.lottery_uid', 'left')
            ->order('Home.status asc Home.id asc')
            ->paginate(20, false);
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
     * 确认赢家
     * @param $homeId
     * @param $winUid
     * @return int|string
     * @throws Exception
     * @throws \think\exception\PDOException
     */
    public static function confirmWin($homeId, $winUid)
    {
        return Db::name('game_home')
            ->where(array(
                'game_home_id' => $homeId,
            ))
            ->update(array(
                'game_home_win_uid' => $winUid,
                'game_home_status' => self::COMPLETE,
                'lottery_at' => date('Y-m-d H:i:s'),
            ));
    }

    /**
     * 游戏完成人数
     * @param $homeId
     * @return int|string
     * @throws Exception
     */
    public static function getCompanyNum($homeId)
    {
        return Db::name('game_record')
            ->where(array(
                'game_home_id' => $homeId,
                'game_status' => 1,
            ))
            ->count();
    }

    /**
     * 分数最高的
     * @param $homeId
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public static function getCompanyMax($homeId)
    {
        return Db::name('game_record')
            ->where(array(
                'game_home_id' => $homeId,
                'game_status' => 1,
            ))
            ->order('grade desc')
            ->find();
    }

    //设置游戏支付状态
    public static function editGamePayStatus($gameRecordId)
    {
        Db::name('game_record')->where('game_record_id', $gameRecordId)->setField('pay_status', self::LOTTERY);
    }

    //设置游戏状态
    public static function editGameRecordStatus($gameRecordId)
    {
        Db::name('game_record')->where('game_record_id', $gameRecordId)->setField('game_status', self::LOTTERY);
    }

    public static function get_max_record($uid, $key = self::GAME_FROG)
    {
        return Db::name('game_record')
            ->where(array(
                'uid' => $uid,
                'game_key' => $key,
                'game_status' => 1,
            ))
            ->order('grade desc')
            ->find();
    }

    public static function get_home_record($home_id, $key = self::GAME_FROG)
    {
        return Db::name('game_record')
            ->field('game_record.*,member.nickname')
            ->join('member', 'game_record.uid=member.uid')
            ->where(array(
                'game_home_id' => $home_id,
                'game_key' => $key,
                'pay_status' => 1,
            ))
            ->order('grade desc')
            ->select();
    }
}

?>