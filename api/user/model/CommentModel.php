<?php
// +----------------------------------------------------------------------
// | 文件说明：评论
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: wuwu <15093565100@163.com>
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Date: 2017-6-4
// +----------------------------------------------------------------------

namespace api\user\model;

use think\Model;

class CommentModel extends Model
{

    //可查询字段
    protected $visible = [
        'id', 'articles.id', 'parent_id', 'user_id', 'to_user_id', 'object_id', 'full_name',
        'email', 'path', 'url', 'content', 'more', 'create_time', 'to_user',
    ];

    /**
     * 基础查询
     */
    protected function base($query)
    {
        $query->where('delete_time', 0)
            ->where('status', 1);
    }

    /**
     * post_content 自动转化
     * @param $value
     * @return string
     */
    public function getContentAttr($value)
    {
        return cmf_replace_content_file_url(htmlspecialchars_decode($value));
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

    public function toUser()
    {
        return $this->belongsTo('UserModel', 'to_user_id');
    }

    /**
     * [CommentList 评论列表获取]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:52:27+0800
     * @since:    1.0
     */
    public function CommentList($map, $limit, $order)
    {
        if (empty($map)) {
            return [];
        }
        $data = $this->with('to_user')->field(true)->where($map)->order($order)->limit($limit)->select();
        return $data;
    }

    public function page($map, $num = 30, $current = 1)
    {
        if (empty($map)) {
            return [];
        }
        $count     = $this->field(true)->where($map)->count(); //总数
        $countPage = ceil($count / $num); //总页数

        if ($countPage <= 1) {
            $data['limit']   = '0,' . $num;
            $data['current'] = 1;
            return $data;
        }
        $nextPage        = ($current - 1) * $num; //下一页
        $data['limit']   = $nextPage . ',' . $num;
        $data['current'] = $current;
        return $data;

    }
}
