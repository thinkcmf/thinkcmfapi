<?php
// +----------------------------------------------------------------------
// | 文件说明：用户-幻灯片
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2017-5-25
// +----------------------------------------------------------------------

namespace api\home\model;

use think\Model;

class SlideModel extends Model
{

    /**
     * [base 全局查询范围status=1显示状态]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T21:54:03+0800
     * @since:    1.0
     */
    protected function base($query)
    {
        $query->where('status', 1);
    }

    /**
     * [SlideItemModel 一对一关联模型 关联分类下的幻灯片]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T23:30:27+0800
     * @since:    1.0
     */
    protected function SlideItemModel()
    {
        return $this->hasMany('SlideItemModel');
    }

    /**
     * [SlideList 幻灯片获取]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:52:27+0800
     * @since:    1.0
     */
    public function SlideList($map)
    {
        $data = $this->relation('slide_item_model')->field(true)->where($map)->select();
        return $data;
    }

}

