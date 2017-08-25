<?php
// +----------------------------------------------------------------------
// | 文件说明：评论
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
use api\user\model\UserModel as User;
use cmf\controller\RestUserBaseController;

class CommentsController extends RestUserBaseController
{

    /**
     * [getUserComments 获取用户评论]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:48:53+0800
     * @since:    1.0
     * @return    [array_json] [获取Comment]
     */
    public function getUserComments()
    {
        $input                   = $this->request->param();
        $id                      = $this->request->has('page') ? $input['page'] : $this->error('page参数不存在');
        $user_id                 = $this->request->has('uid') ? $input['uid'] : $this->getUserId();
        $comment                 = new Comment();
        $map['where']['user_id'] = $user_id;
        $map['order']            = '-create_time';
        //处理不同的情况
        $favoriteData = $comment->getDatas($map);
        $this->success('请求成功', $favoriteData);

    }

    /**
     * [getComments 获取评论]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:48:53+0800
     * @since:    1.0
     * @return    [array_json] [获取Comment]
     */
    public function getComments()
    {
        $input             = $this->request->param();
        $id                = $this->request->has('id') ? $input['id'] : $this->error('id参数不存在');
        $table             = $this->request->has('table') ? $input['table'] : $this->error('table参数不存在');
        $comment           = new Comment();
        $map['object_id']  = $id;
        $map['table_name'] = $table;

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

        $data             = $comment->CommentList($map, $sqldata['limit'], $order);
        $datas['datas']   = $data;
        $datas['current'] = isset($current) ? $current : 1;
        $datas['num']     = isset($num) ? $num : '';
        //数据是否存在
        if ($data->isEmpty()) {
            $this->error('评论数据为空');
        } else {
            $this->success('评论获取成功!', $datas);
        }
    }

    /**
     * [delComments 删除评论]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-08-11T22:08:56+0800
     * @since:    1.0
     * @return
     */
    public function delComments()
    {
        $input = $this->request->param();
        $id    = $this->request->has('id') ? $input['id'] : $this->error('id参数不存在');
        if (Comment::destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * [setComments 添加评论]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-08-16T01:07:44+0800
     * @since:    1.0
     */
    public function setComments()
    {
        $data = $this->_setComments();
        if ($res = Comment::setComment($data)) {
            $this->success('添加成功', $res);
        } else {
            $this->error('添加失败');
        }
    }

    /**
     * [_setComments 评论数据组织]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-08-16T01:00:02+0800
     * @since:    1.0
     */
    protected function _setComments()
    {
        $input              = $this->request->param();
        $data['object_id']  = $this->request->has('oid') ? $input['oid'] : $this->error('oid参数不存在');
        $data['table_name'] = $this->request->has('table') ? $input['table'] : 'portal_post';
        $data['url']        = $this->request->has('url') ? $input['url'] : $this->error('url参数不存在');
        $data['content']    = $this->request->has('content') ? $input['content'] : $this->error('评论参数不存在');
        $data['parent_id']  = $this->request->has('pid') ? $input['pid'] : 0;
        $result             = $this->validate($data,
            [
                'object_id' => 'require|number',
                'content'   => 'require|chs',
                'url'       => 'url',
            ]);
        if (true !== $result) {
            // 验证失败 输出错误信息
            $this->error($result);
        }
        $data['delete_time'] = 0;
        $data['create_time'] = THINK_START_TIME;
        if ($data['parent_id']) {
            $res = Comment::field('parent_id', 'path', 'user_id')->find($data['parent_id']);
            if ($res) {
                $data['path']       = $res['path'] . $data['parent_id'] . ',';
                $data['to_user_id'] = $res['user_id'];
            } else {
                $this->error('回复的评论不存在');
            }
        } else {
            $data['path'] = '0,';
        }
        $data['user_id'] = $this->getUserId();
        $userData        = User::field(true)->find($data['user_id']);
        if (!$userData) {
            $this->error('评论用户不存在');
        }

        $data['full_name'] = $userData['user_nickname'];
        $data['email']     = $userData['user_email'];
        return $data;
    }
}
