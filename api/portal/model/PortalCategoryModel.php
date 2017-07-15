<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\portal\model;

use api\common\model\ParamsFilterModel;

class PortalCategoryModel extends ParamsFilterModel
{
    //类型转换
    protected $type = [
        'more' => 'array',
    ];
    //可查询字段
    protected $visible = [
    				'id','name','description',
    				'seo_title','seo_keywords',
    				'seo_description','more'
    			];
   	protected $relationFilter = [
   					'articles'	=>	'hasToMany'
   				];

    /**
     * 基础查询
     */
    protected function base($query)
    {
      $query->where('delete_time',0)
            ->where('status',1);
    }

   	/**
     * 关联文章表
     * @return $this
     */
    public function articles()
    {
        return $this->belongsToMany('PortalPostModel', 'portal_category_post', 'post_id', 'category_id');
    }
}
