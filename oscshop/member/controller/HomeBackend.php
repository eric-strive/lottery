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

namespace osc\member\controller;

use osc\admin\model\duobaoRecord;
use osc\admin\model\Home;
use osc\admin\model\Member;
use osc\common\controller\AdminBase;
use think\Db;

class HomeBackend extends AdminBase
{

    protected function _initialize()
    {
        parent::_initialize();
        $this->assign('breadcrumb1', '会员');
        $this->assign('breadcrumb2', '订单');
    }

    public function index()
    {
        $this->assign('list', Home::getHomeList());
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        return $this->fetch();
    }

    function update_home()
    {
        if (request()->isPost()) {
            $date = input('post.');
            $id = $date['id'];
            if (empty($id)) {
                $this->error('数据出错');
            }
            $homeInfo = Home::getHomeInfo($id);
            $lottery_num = $date['lottery_num'];
            $status = $date['status'];
            if ($lottery_num > $homeInfo['lottery_drifts']) {
                $this->error('中奖号码不能大于份额');
            }
            if ($homeInfo['status'] == Home::NOT_COMPLETE && !empty($lottery_num)) {
                $u = Db::name('home')->where('id', $id)->update(array(
                    'lottery_num' => $lottery_num,
                ));
                if ($u === false) {
                    $this->error('修改出错，请重新修改');
                }
            }

            if ($homeInfo['status'] == Home::LOTTERY && $status == Home::COMPLETE) {
                Home::confirmGet($id, $homeInfo['lottery_uid']);
            }
            $this->success('编辑成功', url('HomeBackend/index'));
        }
        $this->assign('duobaoRecord', duobaoRecord::getNumbers(input('param.id')));
        $this->assign('data', Db::name('Home')->find(input('param.id')));
        $this->assign('crumbs', '编辑房间');
        return $this->fetch();
    }
}

?>