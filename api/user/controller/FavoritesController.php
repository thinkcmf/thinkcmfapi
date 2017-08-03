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

    }

    /**
     * [_FavoritesObject 收藏数据组装]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-08-03T09:39:06+0800
     * @since:    1.0
     * @return    [type]                    [description]
     */
    protected function _FavoritesObject($id, $table)
    {
        $id    = empty($id) ? $id : return false;
        $table = empty($table) ? $table : return false;

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
