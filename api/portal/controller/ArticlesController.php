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

class ArticlesController extends RestBaseController
{
    protected $postModel;

    public function __construct(PortalPostModel $postModel)
    {
        parent::__construct();
        $this->postModel = $postModel;
    }

    /**
     * 文章列表
     */
    public function index()
    {
        $params = $this->request->get();
        if (isset($params['m'])) {
            switch ($params['m']) {
                case 'recommend':
                    $params['where']['recommended'] = 1;
                    break;
            }
        }
        $params['where']['post_type'] = 1;
        $data                         = $this->postModel->getDatas($params);
        $this->success('请求成功!', $data);
    }

    /**
     * 获取指定的文章
     * @param int $id
     */
    public function read($id)
    {
        if (intval($id) === 0) {
            $this->error('无效的文章id！');
        } else {
            $params                       = $this->request->get();
            $params['where']['post_type'] = 1;
            $params['id']                 = $id;
            $data                         = $this->postModel->getDatas($params);
            $this->success('请求成功!', $data);
        }
    }

    public function search()
    {
	    $params = $this->request->get();
	    if (!empty($params['keyword'])) {
	    	$params['where'] = [
	    		'post_type'  =>  1,
			    'post_title|post_keywords|post_excerpt' =>  ['like','%' . $params['keyword'] . '%']
		    ];
		    $data            = $this->postModel->getDatas($params);
		    $this->success('请求成功!', $data);
	    } else {
	    	$this->error('搜索关键词不能为空！');
	    }
    }
}
