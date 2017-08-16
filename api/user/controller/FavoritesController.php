<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\user\controller;

use api\user\model\UserFavoriteModel;
use cmf\controller\RestUserBaseController;

class FavoritesController extends RestUserBaseController
{
    protected $userFavoriteModel;

    public function __construct(UserFavoriteModel $userFavoriteModel)
    {
        parent::__construct();
        $this->userFavoriteModel = $userFavoriteModel;
    }

    /**
     * 显示收藏列表
     */
    public function getFavorites()
    {
        $userId       = $this->getUserId();
        $favoriteData = $this->userFavoriteModel->where('user_id', $userId)->order('create_time', 'DESC')->select();
        $this->success('请求成功', $favoriteData);
    }

    /**
     * [setFavorites 添加收藏]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-08-03T09:03:40+0800
     * @since:    1.0
     */
    public function setFavorites()
    {
        $input = $this->request->param();
        //组装数据
        $data = $this->_FavoritesObject($input['title'], $input['url'], $input['description'], $input['table'], $input['oid']);
        if (!$data) {
            $this->error('收藏失败');
        }
        if ($this->userFavoriteModel->where('object_id', $input['oid'])->where('table_name', $input['table'])->count() > 0) {
            $this->error('已收藏');
        }
        if ($this->userFavoriteModel->setFavorite($data)) {
            $this->success('收藏成功');
        } else {
            $this->error('收藏失败');
        }

    }

    /**
     * [_FavoritesObject 收藏数据组装]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-08-03T09:39:06+0800
     * @since:    1.0
     * @return    [type]                    [description]
     */
    protected function _FavoritesObject($title, $url, $description, $table_name, $object_id)
    {
        $data['user_id']     = $this->getUserId();
        $data['create_time'] = THINK_START_TIME;

        if (empty($title)) {
            return false;
        } else if (empty($url)) {
            return false;
        } elseif (empty($description)) {
            return false;
        } elseif (empty($table_name)) {
            return false;
        } elseif (empty($object_id)) {
            return false;
        }
        $data['title']       = $title;
        $data['url']         = $url;
        $data['description'] = $description;
        $data['table_name']  = $table_name;
        $data['object_id']   = $object_id;
        return $data;
    }

    /**
     * [unsetFavorites 取消收藏]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-08-03T09:04:31+0800
     * @since:    1.0
     * @return    [type]                    [description]
     */
    public function unsetFavorites()
    {
        $input = $this->request->param();
        if ($this->userFavoriteModel->unsetFavorite($input['oid'], $input['table'])) {
            $this->success('请求成功');
        } else {
            $this->error('请求失败');
        }

    }
}
