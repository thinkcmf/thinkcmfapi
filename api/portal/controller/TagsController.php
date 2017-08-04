<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------
namespace api\portal\controller;

use cmf\controller\RestUserBaseController;
use api\portal\model\PortalTagModel;

class TagsController extends RestUserBaseController
{
    protected $tagModel;

    public function __construct(PortalTagModel $tagModel)
    {
        parent::__construct();
        $this->tagModel = $tagModel;
    }

    /**
     * 获取标签列表
     */
    public function index()
    {
        $params = $this->request->get();
        $data   = $this->tagModel->getDatas($params);
        $this->success('请求成功!', $data);
    }

    /**
     * 获取热门标签列表
     */
    public function hotTags()
    {
        $params                         = $this->request->get();
        $params['where']['recommended'] = 1;
        $data                           = $this->tagModel->getDatas($params);
        $this->success('请求成功!', $data);
    }

    /**
     * 获取标签
     * @param int $id
     */
    public function read($id)
    {
        if (intval($id) === 0) {
            $this->error('无效的文章id！');
        } else {
            $params             = $this->request->get();
            $params['id']       = $id;
            $params['relation'] = 'articles';
            $data               = $this->tagModel->getDatas($params);
            $this->success('请求成功!', $data);
        }
    }
}
