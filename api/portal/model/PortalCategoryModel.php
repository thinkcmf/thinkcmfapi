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
use api\portal\model\PortalCategoryPostModel;

class PortalCategoryModel extends ParamsFilterModel
{
    //类型转换
    protected $type = [
        'more' => 'array',
    ];
    //可查询字段
    protected $visible = [
        'id', 'name', 'description',
        'seo_title', 'seo_keywords',
        'seo_description', 'more', 'PostIds',
    ];
    protected $relationFilter = [
        'articles' => 'hasToMany',
    ];

    /**
     * 基础查询
     */
    protected function base($query)
    {
        $query->where('delete_time', 0)
            ->where('status', 1);
    }

    public static function categoryPostIds($category_id)
    {
    	$ids = '';
        $post_ids = self::relation('PostIds')->field(true)->where('id',$category_id)->find();
        foreach ($post_ids['PostIds'] as $key => $id) {
        	$ids[]= $id['post_id'];
        }
        $post_ids['PostIds'] = $ids;
        return $post_ids;
    }

    public function PostIds(){
    	return self::hasMany('PortalCategoryPostModel','category_id','id');
    }

    /**
     * 关联文章表
     * @return $this
     */
    public function articles()
    {
        return $this->belongsToMany('PortalPostModel', 'portal_category_post', 'post_id', 'category_id');
    }

    /**
     * more 自动转化
     * @param $value
     * @return array
     */
    public function getMoreAttr($value)
    {
        $more = json_decode($value, true);
        if (!empty($more['thumbnail'])) {
            $more['thumbnail'] = cmf_get_image_preview_url($more['thumbnail']);
        }

        if (!empty($more['photos'])) {
            foreach ($more['photos'] as $key => $value) {
                $more['photos'][$key]['url'] = cmf_get_image_preview_url($value['url']);
            }
        }

        if (!empty($more['files'])) {
            foreach ($more['files'] as $key => $value) {
                $more['files'][$key]['url'] = cmf_get_image_preview_url($value['url']);
            }
        }
        return $more;
    }
}
