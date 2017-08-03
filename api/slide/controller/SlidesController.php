<?php
// +----------------------------------------------------------------------
// | 文件说明：幻灯片
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2017-5-25
// +----------------------------------------------------------------------
namespace api\slide\controller;

use api\slide\model\SlideModel;
use cmf\controller\RestBaseController;

class SlidesController extends RestBaseController
{
    /**
     * [getSlides 获取幻灯片]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:48:53+0800
     * @since:    1.0
     * @return    [array_json] [获取幻灯片]
     */
    public function getSlides()
    {
        //slide为空或不存在抛出异常
        if (!$this->request->has('slide') || empty($this->request->param('slide'))) {
            $this->error('缺少slide参数');
        }

        $map['id'] = $this->request->param('slide');
        $obj       = new SlideModel();
        $data      = $obj->SlideList($map);

        //剔除分类状态隐藏 剔除分类下显示数据为空
        if ($data->isEmpty() || empty($data->toArray()[0]['slide_item_model'])) {
            $this->error('该组幻灯片显示数据为空');
        }

        $this->success("该组幻灯片获取成功!", $data);
    }

}
