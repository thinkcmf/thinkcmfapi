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
use api\portal\model\PortalCategoryModel;
use think\Request;

class CategorysController extends RestBaseController
{
    protected $categoryModel;

    public function __construct(PortalCategoryModel $categoryModel)
    {
        $this->categoryModel = $categoryModel;
    }
    /**
     * 显示分类列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $params = Request::instance()->get();
        $datas = $this->categoryModel->getDatas($params);
        $this->success('请求成功!',$datas);
    }

    /**
     * 显示指定的分类
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        $params = Request::instance()->get();
        $params['id'] = $id;
        $datas = $this->categoryModel->getDatas($params);
        $this->success('请求成功!',$datas);
    }
}