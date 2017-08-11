<?php
// +----------------------------------------------------------------------
// | 文件说明：路由
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.wuwuseo.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: thinkcmf
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2017-8-11
// +----------------------------------------------------------------------
use think\Route;

Route::get([
    'user/favorites' => 'user/favorites/getFavorites', //获取收藏列表
]);

Route::post([
    'user/articles/deletes' => 'user/Articles/deletes',
    'user/favorites' => 'user/favorites/setFavorites',//添加收藏
]);

Route::put([

]);

Route::delete([
	'user/favorites/:id'=>'user/favorites/unsetFavorites',//删除收藏
]);
