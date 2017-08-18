<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\portal\model;

use api\common\model\CommonModel;

class PortalPostModel extends CommonModel
{
    //可查询字段
    protected $visible = [
        'id', 'articles.id', 'user_id', 'post_id', 'post_type', 'comment_status',
        'is_top', 'recommended', 'post_hits', 'post_like', 'comment_count',
        'create_time', 'update_time', 'published_time', 'post_title', 'post_keywords',
        'post_excerpt', 'post_source', 'post_content', 'more', 'user_nickname',
        'user', 'category_id'
    ];

    //设置只读字段
    protected $readonly = ['user_id'];
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;
    //类型转换
    protected $type = [
        'more' => 'array',
    ];
    //模型关联方法
    protected $relationFilter = ['user', 'categories'];

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
     * 关联 user表
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo('api\portal\model\UserModel', 'user_id');
    }

    /**
     * 关联 user表
     * @return $this
     */
    public function articleUser()
    {
        return $this->belongsTo('api\portal\model\UserModel', 'user_id')->field('id,user_nickname');
    }

    /**
     * 关联分类表
     * @return $this
     */
    public function categories()
    {
        return $this->belongsToMany('api\portal\model\PortalCategoryModel', 'portal_category_post', 'category_id', 'post_id');
    }

    /**
     * 关联标签表
     * @return $this
     */
    public function tags()
    {
        return $this->belongsToMany('api\portal\model\PortalTagModel', 'portal_tag_post', 'tag_id', 'post_id');
    }

    /**
     * 关联 回收站 表
     */
    public function recycleBin()
    {
        return $this->hasOne('api\portal\model\RecycleBinModel', 'object_id');
    }

    /**
     * published_time   自动转化
     * @param $value
     * @return string
     */
    public function getPublishedTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * published_time   自动转化
     * @param $value
     * @return int
     */
    public function setPublishedTimeAttr($value)
    {
        if (is_numeric($value)) {
            return $value;
        }
        return strtotime($value);
    }

    /**
     * post_content 自动转化
     * @param $value
     * @return string
     */
    public function getPostContentAttr($value)
    {
        return cmf_replace_content_file_url(htmlspecialchars_decode($value));
    }

    /**
     * post_content 自动转化
     * @param $value
     * @return string
     */
    public function setPostContentAttr($value)
    {
        return htmlspecialchars(cmf_replace_content_file_url(htmlspecialchars_decode($value), true));
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
}
