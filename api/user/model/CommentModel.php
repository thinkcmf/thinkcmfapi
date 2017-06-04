<?php
// +----------------------------------------------------------------------
// | 文件说明：评论
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.wuwuseo.com All rights reserved.
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
    
    /**
     * [CommentList 评论列表获取]
     * @Author:   wuwu<15093565100@163.com>
     * @DateTime: 2017-05-25T20:52:27+0800
     * @since:    1.0
     */
    public function CommentList($map,$limit,$order)
    {
        $map = json_decode($map,true);
        if(empty($map)){
            return [];
        }
        $data = $this -> field(true) -> where($map) -> order($order) -> limit($limit) -> select();
        return $data;
    }
	
    public function page($map,$num=30,$current=1){//下拉加载

        $map = json_decode($map,true);
	if(empty($map)){
            return [];
	}
	$count = $this -> field(true) -> where($map) -> count();//总数
	$countPage = ceil($count/$num);//总页数

	if($countPage <= 1){
		$data['limit'] = '0,'.$num;
		$data['current'] = 1;
		return $data;
	}	
	$nextPage = ($current-1)*$num;//下一页
	$data['limit'] = $nextPage.','.$num;
	$data['current'] = $current;
	return $data;

    }
}

