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
		$this->success('添加成功！');
	}

	/**
	 * 显示指定的资源
	 *
	 * @param  int $id
	 */
	public function read($id)
	{
		if (empty($id)) {
			$this->error('无效的文章id');
		}
		$params         =   $this->request->get();
		$params['id']   =   $id;
		$userId         =   $this->getUserId();
		$datas          =   $this->postModel->getUserArticles($userId,$params);
		$this->success('请求成功!', $datas);
	}

	/**
	 * 保存更新的资源
	 *
	 * @param  int $id
	 */
	public function update($id)
	{
		$data              =   $this->request->put();
		$result            =   $this->validate($data, 'Articles.article');
		if ($result !== true) {
			$this->error($result);
		}
		if (empty($id)) {
			$this->error('无效的文章id');
		}
		$result = $this->postModel->editArticle($data,$id,$this->getUserId());
		if ($result === false) {
			$this->error('修改失败！');
		} else {
			$this->success('修改成功！');
		}
	}

	/**
	 * 删除指定资源
	 *
	 * @param  int $id
	 */
	public function delete($id)
	{
		if (empty($id)) {
			$this->error('无效的文章id');
		}
		$result = $this->postModel->deleteArticle($id,$this->getUserId());
		if ($result) {
			$this->success('删除成功！');
		} else {
			$this->error('删除失败！');
		}
	}
}