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

class CategoriesController extends RestBaseController
{
    protected $categoryModel;

    public function __construct(PortalCategoryModel $categoryModel)
    {
        parent::__construct();
        $this->categoryModel = $categoryModel;
    }

    /**
     * 显示分类列表
     *
     * @return \think\Response
     */
    public function index()
    {
        $params = $this->request->get();
        $datas  = $this->categoryModel->getDatas($params);
        $this->success('请求成功!', $datas);
    }

    /**
     * 显示指定的分类
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {
        $params       = $this->request->get();
        $params['id'] = $id;
        $datas        = $this->categoryModel->getDatas($params);
        $this->success('请求成功!', $datas);
    }
}