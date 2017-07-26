<?php
// +----------------------------------------------------------------------
// | 文件说明：我的评论
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2017-7-26
// +----------------------------------------------------------------------
namespace api\user\controller;

use api\user\model\CommentModel as Comment;
use cmf\controller\RestUserBaseController;

class CommentsController extends RestUserBaseController
{

    /**
     * [getComments 获取评论]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:48:53+0800
     * @since:    1.0
     * @return    [array_json] [获取Comment]
     */
    public function getComments()
    {
        $input          = $this->request->param();
        $user_id        = $this->request->has('uid') ? $input['uid'] : $this->error('userid不能为为空');
        $comment        = new Comment();
        $map['user_id'] = $user_id;

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

        $order = 'id DESC';

        $data = $comment->CommentList($map, $sqldata['limit'], $order);
        $datas['datas'] = $data;
        $datas['current'] = isset($current)?$current:1;
        $datas['num'] = isset($num)?$num:'';
        //数据是否存在
        if ($data->isEmpty()) {
            $this->error('评论数据为空');
        } else {
            $this->success('评论获取成功!', $datas);
        }
    }
}
