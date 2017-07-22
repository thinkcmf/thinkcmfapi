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
use api\portal\model\PortalCategoryModel as PortalCategory;

class PortalPostModel extends ParamsFilterModel
{
    //可查询字段
    protected $visible = [
        'id', 'articles.id', 'user_id', 'post_id', 'post_type', 'comment_status',
        'is_top', 'recommended', 'post_hits', 'post_like', 'comment_count',
        'create_time', 'update_time', 'published_time', 'post_title', 'post_keywords',
        'post_excerpt', 'post_source', 'post_content', 'more', 'user_nickname',
        'user', 'category_id'
    ];
    //模型关联方法
    protected $relationFilter = ['user'];

    /**
     * 基础查询
     */
    protected function base($query)
    {
        $query->where('delete_time', 0)
            ->where('post_status', 1)
            ->whereTime('published_time', 'between', [1, time()]);
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
            $more['thumbnail'] = cmf_get_image_url($more['thumbnail']);
        }

        if (!empty($more['photos'])) {
            foreach ($more['photos'] as $key => $value) {
                $more['photos'][$key]['url'] = cmf_get_image_url($value['url']);
            }
        }

        if (!empty($more['files'])) {
            foreach ($more['files'] as $key => $value) {
                $more['files'][$key]['url'] = cmf_get_image_url($value['url']);
            }
        }
        return $more;
    }

    /**
     * 关联 user表
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('UserModel', 'user_id');
    }

    /**
     * 关联 user表
     * @return $this
     */
    public function articleUser()
    {
        return $this->belongsTo('UserModel', 'user_id')->field('id,user_nickname');
    }

    /**
     * 获取相关文章
     * @param int|string|array $postIds 文章id
     * @return array
     */
    public function getRelationPosts($postIds)
    {
        $posts = $this->with('articleUser')
            ->field('id,post_title,user_id,is_top,post_hits,post_like,comment_count,more')
            ->whereIn('id', $postIds)
            ->select();
        foreach ($posts as $post) {
            $post->appendRelationAttr('articleUser', 'user_nickname');
        }
        return $posts;
    }

    /**
     * [recommendedList 推荐列表]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-07-17T11:06:47+0800
     * @since:    1.0
     * @param     integer $next_id [最后索引值]
     * @param     integer $num [一页多少条 默认10]
     * @return    [type]                            [数据]
     */
    public static function recommendedList($next_id = 0, $num = 10)
    {
        $limit = "{$next_id},{$num}";
        $field = 'id,recommended,user_id,post_like,post_hits,comment_count,create_time,update_time,published_time,post_title,post_excerpt,more';
        $list  = self::with('user')->field($field)->where('recommended', 1)->order('published_time DESC')->limit($limit)->select();
        return $list;
    }

    /**
     * [categoryPostList 分类文章列表]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-07-17T15:16:26+0800
     * @since:    1.0
     * @param     [type]                   $category_id [分类ID]
     * @param     integer $next_id [limit索引]
     * @param     integer $num [limit每页数量]
     * @return    [type]                                [description]
     */
    public static function categoryPostList($category_id, $next_id = 0, $num = 10)
    {
        $limit    = "{$next_id},{$num}";
        $Postlist = PortalCategory::categoryPostIds($category_id);
        $field    = 'id,recommended,user_id,post_like,post_hits,comment_count,create_time,update_time,published_time,post_title,post_excerpt,more';
        $list     = self::with('user')->field($field)->whereIn('id', $Postlist['PostIds'])->order('published_time DESC')->limit($limit)->select();
        return $list;
    }
}
