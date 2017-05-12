<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +----------------------------------------------------------------------
namespace api\home\controller;

use think\Db;
use think\Validate;
use cmf\controller\RestBaseController;

class IndexController extends RestBaseController
{
    // api 首页
    public function index()
    {
        $this->success("恭喜您,API访问成功!", ['doc' => 'http://www.kancloud.cn/thinkcmf/cmf5api']);
    }

}
