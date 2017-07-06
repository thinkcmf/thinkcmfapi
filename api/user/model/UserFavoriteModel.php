<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\user\model;

use think\Model;

class UserFavoriteModel extends Model
{
    protected $dateFormat = 'Y-m-d H:i:s';

    /**
     * [base 基础查询条件]
     */
	protected function base($query)
    {
        $query -> field('id,title,url,description,create_time');
    }

    /**
     * 关联表
     * @param  [string] $table_name [关联表名]
     */
    protected function unionTable($table_name)
    {
    	return $this->hasOne($table_name.'Model','object_id');
    }

    /**
     * 获取收藏内容
     * @param  [array] $data [select,find查询结果]
     * @return [array]       [收藏对应表的内容]
     */
    public function getFavorite($data)
    {
    	if (!is_string($data[0])) {
    		foreach ($data as $key => $value) {
    			$where[$value['table_name']][] = $value['object_id'];
    		}
    		foreach ($where as $key => $value) {
    			$favoriteData[] = $this->unionTable($key)->select($value);
    		}
    	}else{
			$favoriteData =  $this->unionTable($data['table_name'])->find($data['object_id']);
    	}

    	return $favoriteData;
    }
}
