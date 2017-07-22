<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\user\controller;

use cmf\controller\RestBaseController;
use api\user\model\CommentModel;

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
        $this->success('请求成功!', $datas);
    }

    /**
     * 显示指定的评论
     *
     * @param  int $id
     * @return \think\Response
     */
    public function read($id)
    {

    }

    /**
     * [getComment 获取评论]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:48:53+0800
     * @since:    1.0
     * @return    [array_json] [获取Comment]
     */
    public function getComment()
    {
        //为空或不存在抛出异常
        if (!$this->request->has('map') || empty($this->request->param('map'))) {
            $this->error(['code' => 0, 'msg' => '缺少map参数']);
        }

        $comment = new Comment();
        $map     = htmlspecialchars_decode($this->request->get('map', '', false));

        //处理不同的情况
        if (!$this->request->has('current') || empty($this->request->param('current'))) {

            if (!$this->request->has('num') || empty($this->request->param('num'))) {
                $sqldata = $comment->page($map);
            } else {
                $num     = $this->request->param('num');
                $sqldata = $comment->page($map, $num);
            }

        } else {
            $current = $this->request->param('current');
            if (!$this->request->has('num') || empty($this->request->param('num'))) {
                $sqldata = $comment->page($map, 30, $current);
            } else {
                $num     = $this->request->param('num');
                $sqldata = $comment->page($map, $num, $current);
            }
        }


        if (!$this->request->has('order') || empty($this->request->param('order'))) {
            $order = 'id desc';
        } else {
            $order = $this->request->param('order');
        }

        if (empty($sqldata)) {
            $this->error(['code' => 0, 'msg' => 'map参数不是json']);
        }

        $data = $comment->CommentList($map, $sqldata['limit'], $order);

        //数据是否存在
        if ($data->isEmpty()) {
            $this->error(['code' => 0, 'msg' => '评论数据为空']);
        } else {
            $this->success(['code' => 1, 'msg' => '评论获取成功!', 'current' => $sqldata['current'], 'data' => $data]);
        }
    }
}
