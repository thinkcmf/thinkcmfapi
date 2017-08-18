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
     * 文章列表
     */
    public function index()
    {
        $params                       = $this->request->get();
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
            if (empty($data)) {
                $this->error('文章不存在！');
            } else {
                $this->success('请求成功!', $data);
            }

        }
    }

    /**
     * 我的文章列表
     */
    public function my()
    {
        $params = $this->request->get();
        $userId = $this->getUserId();
        $data   = $this->postModel->getUserArticles($userId, $params);
        $this->success('请求成功!', $data);
    }

    /**
     * 添加文章
     */
    public function save()
    {
        $data            = $this->request->post();
        $data['user_id'] = $this->getUserId();
        $result          = $this->validate($data, 'Articles.article');
        if ($result !== true) {
            $this->error($result);
        }

        if (empty($data['published_time'])) {
            $data['published_time'] = time();
        }

        $this->postModel->addArticle($data);
        $this->success('添加成功！');
    }

    /**
     * 更新文章
     * @param  int $id
     */
    public function update($id)
    {
        $data   = $this->request->put();
        $result = $this->validate($data, 'Articles.article');
        if ($result !== true) {
            $this->error($result);
        }
        if (empty($id)) {
            $this->error('无效的文章id');
        }
        $result = $this->postModel->editArticle($data, $id, $this->getUserId());
        if ($result === false) {
            $this->error('编辑失败！');
        } else {
            $this->success('编辑成功！');
        }
    }

    /**
     * 删除文章
     * @param  int $id
     */
    public function delete($id)
    {
        if (empty($id)) {
            $this->error('无效的文章id');
        }
        $result = $this->postModel->deleteArticle($id, $this->getUserId());
        if ($result == -1) {
            $this->error('文章已删除');
        }
        if ($result) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    /**
     * 批量删除文章
     */
    public function deletes()
    {
        $ids = $this->request->post('ids/a');
        if (empty($ids)) {
            $this->error('文章id不能为空');
        }
        $result = $this->postModel->deleteArticle($ids, $this->getUserId());
        if ($result == -1) {
            $this->error('文章已删除');
        }
        if ($result) {
            $this->success('删除成功！');
        } else {
            $this->error('删除失败！');
        }
    }

    public function search()
    {
        $params = $this->request->get();
        if (!empty($params['keyword'])) {
            $params['where'] = [
                'post_type'                             => 1,
                'post_title|post_keywords|post_excerpt' => ['like', '%' . $params['keyword'] . '%']
            ];
            $data            = $this->postModel->getDatas($params);
            $this->success('请求成功!', $data);
        } else {
            $this->error('搜索关键词不能为空！');
        }

    }
}