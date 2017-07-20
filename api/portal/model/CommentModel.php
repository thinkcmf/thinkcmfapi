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

	class CommentModel extends ParamsFilterModel
	{
		//可查询字段
		protected $visible = [
			'id', 'articles.id', 'parent_id', 'user_id', 'to_user_id', 'object_id', 'full_name',
			'email', 'path', 'url', 'content', 'more', 'create_time'
		];
		//模型关联方法
		protected $relationFilter = ['user'];

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
			return $this->belongsTo('UserModel','user_id');
		}
	}
