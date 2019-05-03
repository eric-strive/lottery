<?php

namespace osc\member\controller;

use osc\admin\model\Home;
use osc\admin\model\LuckRecord;
use osc\admin\model\Member;
use osc\common\controller\AdminBase;
use think\Db;

class LuckBuy extends AdminBase
{

    protected function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        $this->assign('list', LuckRecord::getRecord());
        $this->assign('empty', '<tr><td colspan="20">没有数据~</td></tr>');

        return $this->fetch();
    }

    function edit()
    {
        if (request()->isPost()) {
            $date = input('post.');
            $id = $date['id'];
            if (empty($id)) {
                $this->error('数据出错');
            }
            Db::name('luck_record')->where('luck_record_id', $id)->setField('is_draw', $date['is_draw']);
            $this->success('编辑成功', url('LuckBuy/index'));
        }
        $this->assign('data', Db::name('luck_record')->find(input('param.id')));
        $this->assign('crumbs', '编辑幸运购数据');
        return $this->fetch();
    }
}

?>