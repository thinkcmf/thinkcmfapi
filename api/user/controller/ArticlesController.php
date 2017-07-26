<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------
namespace api\user\controller;

use cmf\controller\RestUserBaseController;
use api\user\model\PortalPostModel;

class ArticlesController extends RestUserBaseController
{
	protected $postModel;

	public function __construct(PortalPostModel $postModel)
	{
		parent::__construct();
		$this->postModel = $postModel;
	}

	/**
	 * 显示资源列表
	 */
	public function index()
	{
		$params     =   $this->request->get();
		$userId     =   $this->getUserId();
		$datas      =   $this->postModel->getUserArticles($userId,$params);
		$this->success('请求成功!', $datas);
	}

	/**
	 * 保存新建的资源
	 */
	public function save()
	{
		$datas             =   $this->request->post();
		$datas['user_id']  =   $this->getUserId();
		$result            =   $this->validate($datas, 'Articles.article');
		if ($result !== true) {
			$this->error($result);
		}
		$this->postModel->addArticle($datas);
		$this->success('文章添加成功！');
	}

	/**
	 * 显示指定的资源
	 *
	 * @param  int $id
	 */
	public function read($id)
	{
	}

	/**
	 * 保存更新的资源
	 *
	 * @param  int $id
	 */
	public function update($id)
	{
		$datas             =   $this->request->param();
		$result            =   $this->validate($datas, 'Articles.article');
		if ($result !== true) {
			$this->error($result);
		}
		return $this->postModel->editArticle($datas);
		$this->success('文章添加成功！');
	}

	/**
	 * 删除指定资源
	 *
	 * @param  int $id
	 */
	public function delete($id)
	{
	}

}