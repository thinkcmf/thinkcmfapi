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

class PagesController extends RestBaseController
{
    protected $postModel;

    public function __construct(PortalPostModel $postModel)
    {
        $this->postModel = $postModel;
    }
    /**
     * 显示单页列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $params = Request::instance()->get();
        $params['where']['post_type'] = 2;
        $datas = $this->postModel->getParamsFieldDatas($params);
        $this->success('请求成功!',$datas);
    }

    /**
     * 显示指定的单页
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $params = Request::instance()->get();
        $params['where']['post_type'] = 2;
        $params['ids'] = $id;
        $datas = $this->postModel->getParamsFieldDatas($params);
        $this->success('请求成功!',$datas);
    }
}
