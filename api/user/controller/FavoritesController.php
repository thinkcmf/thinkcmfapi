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
    public function index()
    {
        $userId       = $this->getUserId();
        $favoriteData = $this->userFavoriteModel->where('user_id', $userId)->order('create_time', 'DESC')->select();
        $this->success('请求成功', $favoriteData);
    }

    public function save()
    {

    }

    public function read()
    {
        echo "dd";

    }

    public function update()
    {

    }

    public function delete()
    {

    }

}
