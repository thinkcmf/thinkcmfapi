<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
namespace api\portal\controller;

use api\portal\controller\CommonController;
use api\portal\model\PortalCategoryModel as PortalCategory;
use api\portal\model\PortalPostModel as PortalPost;
use think\exception\HttpResponseException;
use think\Response;

class ListController extends CommonController
{

    /**
     * [getRecommendedList 推荐列表]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-07-17T11:36:51+0800
     * @since:    1.0
     * @return    [type]                   [json数据]
     */
    public function getRecommendedList()
    {
        $num     = $this->request->has('num') ? $this->request->param('num') : 10;
        $next_id = $this->request->has('next_id') ? $this->request->param('next_id') : 0;
        $list    = PortalPost::recommendedList($next_id, $num);
        $limit   = [$next_id, $num];
        $this->success('ok', $list, $limit);
    }

    public function getCategoryPostList()
    {
        $num         = $this->request->has('num') ? $this->request->param('num') : 10;
        $next_id     = $this->request->has('next_id') ? $this->request->param('next_id') : 0;
        $category_id = $this->request->has('category_id') ? $this->request->param('category_id') : 1;
        if (!PortalCategory::where('id', $category_id)->find()) {
            $this->error('fail');
        }
        $list  = PortalPost::categoryPostList($category_id, $next_id, $num);
        $limit = [$next_id, $num];
        $this->success('ok', $list, $limit);
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param mixed $msg 提示信息
     * @param mixed $data 返回的数据
     * @param array $header 发送的Header信息
     * @return void
     */
    protected function success($msg = '', $data = '', array $limit = [], array $header = [])
    {
        $code   = 1;
        $result = [
            'code'  => $code,
            'msg'   => $msg,
            'limit' => $limit,
            'data'  => $data,
        ];

        $type     = $this->getResponseType();
        $response = Response::create($result, $type)->header($header);
        throw new HttpResponseException($response);
    }

}
