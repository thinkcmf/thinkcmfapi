<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\portal\controller;

use cmf\controller\RestBaseController;
use api\portal\model\PortalPostModel;
use think\Request;

class ArticlesController extends RestBaseController
{
    protected $postModel;

    public function __construct(PortalPostModel $postModel)
    {
        $this->postModel = $postModel;
    }
    /**
     * 显示文章列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $params = Request::instance()->get();
        $params['where']['post_type'] = 1;
        $datas = $this->postModel->getDatas($params);
        $this->success('请求成功!',$datas);
    }

    /**
     * 显示指定的文章
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $params = Request::instance()->get();
        $params['where']['post_type'] = 1;
        $params['id'] = $id;
        $datas = $this->postModel->getDatas($params);
        $this->success('请求成功!',$datas);
    }
}
