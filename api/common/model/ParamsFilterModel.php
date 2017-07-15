<?php
	// +----------------------------------------------------------------------
	// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
	// +----------------------------------------------------------------------
	// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
	// +----------------------------------------------------------------------
	// | Author: pl125 <xskjs888@163.com>
	// +----------------------------------------------------------------------
	namespace api\common\model;
	use think\Model;
	use think\Loader;
	class ParamsFilterModel extends Model
	{
		//  关联模型过滤
		protected $relationFilter = [
		];
		/**
		 * @access public
		 * @param array    $params  过滤参数
		 * @return array|collection  查询结果
		 */
		public function getDatas($params = '')
		{
			if (empty($params)) {
				return $this->select();
			}
			$this->setCondition($params);
			if (!empty($params['id'])) {
				$datas = $this->find();
			} else {
				$datas = $this->select();
			}
			if (!empty($params['relation'])) {
				if ($this->isWhite($params['relation'])) {
					$articles = [];
					if (!empty($params['id'])) {
						$code = 'return $datas->' . $params["relation"] . ';';
						$articles[] = eval($code);
					} else {
						foreach ($datas as $article) {
							$code = 'return $article->' . $params["relation"] . ';';
							$articles[] = eval($code);
						}
					}
				}
			}
			if (empty($articles)) {
				return $datas;
			} else {
				return [$datas,$articles];
			}
		}
		/**
		 * @access public
		 * @param array    $params  过滤参数
		 * @param array    $relationParams 关联模型过滤参数
		 * @return $this
		 */
		public function setCondition($params)
		{
			if (empty($params)) {
				return $this;
			}
			if (!empty($params['relation'])) {
				$relations = is_string($params['relation']) ? explode(',' , $params['relation']) : $params['relation'];
				foreach ($this->relationFilter as $key => $value) {
					foreach ($relations as $index=>$relation) {
						$relation = Loader::parseName($relation, 1, false);
						if ($relation == $key) {
							$paramsRelations[] = $key;
							$relationType[] = $value;
							$relationParams[] = isset($params['relationParams'][$index]) ? $params['relationParams'][$index] : '';
						}
					}
				}
				if (!empty($paramsRelations)) {
					if (count($paramsRelations) == 1) {
						$relationName = $paramsRelations[0];
						$model = $this->$relationName();
						if (!empty($params['relationParams'])) {
							$withParams = $this->setWithmethodParams($params['relationParams'],$model,['name'=>$paramsRelations[0],'relationType'=>$relationType[0]]);
						} else {
							$withParams = $paramsRelations[0];
						}
					} else {
						foreach ($paramsRelations as $k => $relation) {
							$model = $this->$relation();
							if (isset($relationParams[$k])) {
								$withParams[] = $this->setWithmethodParams($params['relationParams'][$k],$model,['name'=>$relation,'relationType'=>$relationType[$k]]);
							} else {
								$withParams[] = $relation;
							}
						}
					}
					if (!empty($withParams)) {
						$this->with($withParams);
					}
				}
			}
			return $this->paramsFilter($params);
		}
		/**
		 * @access public
		 * @param array    $params  过滤参数
		 * @param Model    $model 关联模型
		 * @return $this
		 */
		public function paramsFilter($params,$model = '')
		{
			if (!empty($model)) {
				$condition = [];
				$_this = $model;
			} else {
				$_this = $this;
			}
			if (isset($_this->visible)) {
				$whiteParams = $_this->visible;
			}
			if (!empty($params['field'])) {
				if (!empty($whiteParams)) {
					if (is_string($params['field'])) {
						$filterParams = explode(',',$params['field']);
					}
					$mixedField = array_intersect($filterParams,$whiteParams);
				}
				if (!empty($mixedField)) {
					if (empty($model)) {
						$_this->field($mixedField);
					} else {
						$condition['field'] = $mixedField;
					}
				}
			}
			if (!empty($params['id'])) {
				$id = intval($params['id']);
				if (!empty($id)) {
					if (empty($model)) {
						$_this->where('id',$id);
					} else {
						$condition['id'] = $id;
					}
					if (isset($condition)) {
						return $condition;
					} else {
						return $_this;
					}
				}
			} elseif (!empty($params['ids'])) {
				$ids = $params['ids'];
				if (is_string($ids)) {
					$ids = explode(',',$params['ids']);
				}
				if (is_array($ids)) {
					foreach ($ids as $key => $value) {
						$ids[$key] = intval($value);
					}
				} else {
					$ids = [intval($ids)];
				}
				if (empty($model)) {
					$_this->where('id','in',$ids);
				} else {
					$condition['ids'] = ['id','in',$ids];
				}
			}
			if (!empty($params['limit'])) {
				if (is_string($params['limit']) || is_numeric($params['limit'])) {
					if (is_string($params['limit'])) {
						$limit = explode(',',$params['limit']);
						foreach ($limit as $key => $value) {
							$limit[$key] = intval($value);
						}
					} else {
						$limit = [intval($params['limit'])];
					}
					if (count($limit) == 1) {
						if (empty($model)) {
							$_this->limit($limit[0]);
						} else {
							$condition['limit'] = $limit[0];
						}
					} elseif (count($limit) == 2) {
						if (empty($model)) {
							$_this->limit($limit[0],$limit[1]);
						} else {
							$condition['limit'] = $limit[0] . ',' . $limit[1];
						}
					}
				}
			}
			if (!empty($params['page'])) {
				if (is_string($params['page']) || is_numeric($params['page'])) {
					if (is_string($params['page'])) {
						$page = explode(',',$params['page']);
						foreach ($page as $key => $value) {
							$page[$key] = intval($value);
						}
					} else {
						$page = [intval($params['page'])];
					}
					if (count($page) == 1) {
						if (empty($model)) {
							$_this->page($page[0]);
						} else {
							$condition['page'] = $page[0];
						}
					} elseif (count($page) == 2) {
						if (empty($model)) {
							$_this->page($page[0],$page[1]);
						} else {
							$condition['page'] = $page[0] . ',' . $page[1];
						}
					}
				}
			}
			if (!empty($params['order']) && is_string($params['order'])) {
				$order = explode(',',$params['order']);
				foreach ($order as $key => $value) {
					$upDwn = substr($value,0,1);
					$orderType = $upDwn == '-' ? 'desc' : 'asc';
					$orderField = substr($value,1);
					if (!empty($whiteParams)) {
						if (in_array($orderField,$whiteParams)) {
							$orderWhere[$orderField] = $orderType;
						}
					} else {
						$orderWhere[$orderField] = $orderType;
					}
				}
				if (!empty($orderWhere)) {
					if (empty($model)) {
						$_this->order($orderWhere);
					} else {
						$condition['order'] = $orderWhere;
					}
				}
			}
			if (isset($condition)) {
				return $condition;
			} else {
				return $_this;
			}
		}
		/**
		 * 设置预载入条件
		 * @access public
		 * @param array $relationParams 关联模型条件参数
		 * @param model $model 关联模型
		 * @param array $relation 关联方法参数
		 * @return boolean
		 */
		public function setWithmethodParams($relationParams,$model,$relation)
		{
			$relationWhere = $this->paramsFilter($relationParams,$model);
			$relationWhere['relationType'] = $relation['relationType'];
			return [
				$relation['name'] => function ($query) use ($relationWhere) {
					if (!empty($relationWhere['field'])) {
						if ($relationWhere['type'] == 'hasOne' || $relationWhere['relationType'] == 'belongsTo') {
							$query->withField($relationWhere['field']);
						} else {
							$query->field($relationWhere['field']);
						}
					}
					if (!empty($relationWhere['ids'])) {
						$query->where($relationWhere['ids']);
					}
					if (!empty($relationWhere['where'])) {
						$query->where($relationWhere['where']);
					}
					if (!empty($relationWhere['limit'])) {
						$query->where($relationWhere['limit']);
					}
					if (!empty($relationWhere['page'])) {
						$query->where($relationWhere['page']);
					}
					if (!empty($relationWhere['order'])) {
						$query->where($relationWhere['order']);
					}
				}
			];
		}
		/**
		 * 是否允许关联
		 * @access public
		 * @param string $relationName 模型关联方法名
		 * @return boolean
		 */
		public function isWhite($relationName)
		{
			$name = Loader::parseName($relationName, 1, false);
			foreach ($this->relationFilter as $key => $value) {
				if ($name == $key) {
					return true;
				} else{
					return false;
				}
			}
		}
	}