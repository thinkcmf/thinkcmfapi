<?php

namespace api\portal\controller;

use cmf\controller\RestBaseController;
use api\portal\model\CommentModel;

class CommentsController extends RestBaseController
{
	protected $commentModel;

	public function __construct(CommentModel $commentModel)
	{
		parent::__construct();
		$this->commentModel = $commentModel;
	}
    /**
     * 显示评论列表
     *
     * @return \think\Response
     */
    public function index()
    {
	    $params = $this->request->get();
	    if (isset($params['o_id'])) {
		    $object_id = intval($params['o_id']);
		    if (!empty($object_id)) {
			    $params['where']['object_id'] = $object_id;
		    }
	    }
	    $datas = $this->commentModel->getDatas($params);
	    $this->success('请求成功!',$datas);
    }

    /**
     * 显示指定的评论
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {

    }
}
