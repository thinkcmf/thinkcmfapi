<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\portal\model;

<<<<<<< HEAD
use think\Model;

class UserModel extends Model
=======
use api\common\model\CommonModel;

class UserModel extends CommonModel
>>>>>>> a04cbbac0deeb4a231f506e2628cb339c953d35f
{
    //可查询字段
    protected $visible = [
        'articles.id', 'user_nickname', 'avatar', 'signature'
    ];
    //模型关联方法
    protected $relationFilter = ['user'];

    /**
     * 基础查询
     */
    protected function base($query)
    {
        $query->where('cmf_user.user_status', 1);
    }

    /**
     * more 自动转化
     * @param $value
     * @return array
     */
    public function getAvatarAttr($value)
    {
        $value = !empty($value) ? cmf_get_image_url($value) : $value;
        return $value;
    }

    /**
     * 关联 user表
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('UserModel', 'user_id')->setEagerlyType(1);
    }
}
