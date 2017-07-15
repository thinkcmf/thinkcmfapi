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

class PortalPostModel extends ParamsFilterModel
{
    //可查询字段
    protected $visible = [
    				'id','post_type','comment_status','is_top','recommended',
    				'post_hits','post_like','comment_count','published_time',
    				'post_title','post_keywords','post_excerpt','post_source',
    				'post_content','more'
    			];

   	/**
   	 * 基础查询
   	 */
   	protected function base($query)
   	{
   		$query->where('delete_time',0)
   			  ->where('post_status',1)
   			  ->whereTime('published_time','between',[1,time()]);
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
     * more 自动转化
     * @param $value
     * @return array
     */
    public function getMoreAttr($value)
    {
    	$more = json_decode($value,true);
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
