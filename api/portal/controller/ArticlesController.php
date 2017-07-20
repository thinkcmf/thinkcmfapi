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
use api\portal\model\PortalTagPostModel;

class ArticlesController extends RestBaseController
{
    protected $postModel;

    public function __construct(PortalPostModel $postModel)
    {
    	parent::__construct();
        $this->postModel = $postModel;
    }
    /**
     * 显示文章列表
     *
     * @return \think\Response
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
    	if (intval($id) === 0) {
		    $this->error('无效的文章id！');
	    } else {
		    $params = $this->request->get();
		    $params['where']['post_type'] = 1;
		    $params['id'] = $id;
		    $datas = $this->postModel->getDatas($params);
		    $tagModel = new PortalTagPostModel;
		    $postIds = $tagModel->getRelationPostIds($id);
		    $posts = $this->postModel->getRelationPosts($postIds);
		    $this->success('请求成功!',[$datas,$posts]);
	    }
    }
}
