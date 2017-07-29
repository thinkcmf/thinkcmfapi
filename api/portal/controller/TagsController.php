<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------
namespace api\portal\controller;

use cmf\controller\RestUserBaseController;
use api\portal\model\PortalTagModel;
class TagsController extends RestUserBaseController
{
	protected $tagModel;

	public function __construct(PortalTagModel $tagModel)
	{
		parent::__construct();
		$this->tagModel = $tagModel;
	}
	/**
	 * 显示标签列表
	 */
	public function index()
	{
		$params = $this->request->get();
		$datas  = $this->tagModel->getDatas($params);
		$this->success('请求成功!', $datas);
	}
	/**
	 * 获取热门标签列表
	 */
	public function hotTags()
	{
		$params =   $this->request->get();
		$params['where']['recommended'] = 1;
		$datas  = $this->tagModel->getDatas($params);
		$this->success('请求成功!', $datas);
	}
	/**
	 * 显示指定的标签
	 *
	 * @param  int $id
	 */
	public function read($id)
	{
		if (intval($id) === 0) {
			$this->error('无效的文章id！');
		} else {
			$params                       = $this->request->get();
			$params['id']                 = $id;
			$params['relation']           = 'articles';
			$datas                        = $this->tagModel->getDatas($params);
			$this->success('请求成功!', $datas);
		}
	}
}
