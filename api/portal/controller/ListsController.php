<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
namespace api\portal\controller;

use api\portal\model\PortalCategoryModel as PortalCategory;
use api\portal\model\PortalPostModel as PortalPost;
use cmf\controller\RestBaseController;

class ListsController extends RestBaseController
{

    /**
     * [推荐文章列表]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-07-17T11:36:51+0800
     * @since:    1.0
     */
    public function recommended()
    {
        $num           = $this->request->has('num') ? $this->request->param('num') : 10;
        $next_id       = $this->request->has('next_id') ? $this->request->param('next_id') : 0;
        $list['list']  = PortalPost::recommendedList($next_id, $num);
        $list['limit'] = [$next_id, $num];
        $this->success('ok', $list);
    }

    /**
     * [getCategoryPostLists 分类文章列表]
     * @Author:    wuwu<15093565100@163.com>
     * @DateTime: 2017-07-17T15:22:41+0800
     * @since:    1.0
     */
    public function getCategoryPostLists()
    {
        $num         = $this->request->has('num') ? $this->request->param('num') : 10;
        $next_id     = $this->request->has('next_id') ? $this->request->param('next_id') : 0;
        $category_id = $this->request->has('category_id') ? $this->request->param('category_id') : 1;
        //分类是否存在
        if (!PortalCategory::where('id', $category_id)->find()) {
            $this->error('fail');
        }
        $list['datas'] = PortalPost::categoryPostList($category_id, $next_id, $num);
        $list['limit'] = [$next_id, $num];
        $this->success('ok', $list);
    }

}
