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

class CommonModel extends Model
{
    //  关联模型过滤
    protected $relationFilter = [];

    /**
     * @access public
     * @param array $params 过滤参数
     * @return array|collection  查询结果
     */
    public function getDatas($params = [])
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
                if (!empty($params['id'])) {
                    $relationParams =   $params;
                    unset($relationParams['id']);
                    if (!empty($relationParams)) {
                        // 获取关联模型实例
	                    $relation       =   $params['relation'];
	                    $_queryObject   =   $datas->$relation();
                        $modelName      =    '\\' . $_queryObject->getmodel();
                        $relationModel  =    new $modelName;
                        //设置关联模型条件过滤
                        $relationParams =   $this->paramsFilter($relationParams, $relationModel);
                        $relationModel  =   $this->setParamsQuery($relationParams, $_queryObject);
                        $datas          =   $datas->toArray();
                        $datas[$params['relation']]       =   $relationModel->select();
                    }
                }
            }
        }
        return $datas;
    }

    /**
     * @access public
     * @param array $params 过滤参数
     * @param array $relationParams 关联模型过滤参数
     * @return $this
     */
    public function setCondition($params)
    {
        if (empty($params)) {
            return $this;
        }
        if (!empty($params['relation'])) {
            $relations       = $this->strToArr($params['relation']);
            $enableRelations = array_intersect($relations, $this->relationFilter);
            if (!empty($enableRelations)) {
                if (!empty($params['id']) && count($enableRelations == 1)) {
                    $this->paramsFilter($params);
                } else {
                    $this->paramsFilter($params)->with($enableRelations);
                }
            }
        } else {
            $this->paramsFilter($params);
        }
        return $this;
    }

    /**
     * @access public
     * @param array $params 过滤参数
     * @param model $model 关联模型
     * @return model|array  $this|链式查询条件数组
     */
    public function paramsFilter($params, $model = '')
    {
        if (!empty($model)) {
            $condition = [];
            $_this     = $model;
        } else {
            $_this = $this;
        }

        if (isset($_this->visible)) {
            $whiteParams = $_this->visible;
        }
        // 设置field字段过滤
        if (!empty($params['field'])) {
            if (!empty($whiteParams)) {
                $filterParams = $this->strToArr($params['field']);
                $mixedField   = array_intersect($filterParams, $whiteParams);
            }
            if (!empty($mixedField)) {
                if (empty($model)) {
                    $_this->field($mixedField);
                } else {
                    $key = array_search('id', $mixedField);
                    if (false !== $key) {
                        $mixedField[$key] = 'articles.id';
                    }
                    $condition['field'] = $mixedField;
                }
            }
        }
        // 设置id，ids
        if (!empty($params['ids'])) {
            $ids = $this->strToArr($params['ids']);
            foreach ($ids as $key => $value) {
                $ids[$key] = intval($value);
            }
        }
        if (!empty($params['id'])) {
            $id = intval($params['id']);
            if (!empty($id)) {
                return $_this->where('id', $id);
            }
        } elseif (!empty($ids)) {
            if (empty($model)) {
                $_this->where('id', 'in', $ids);
            } else {
                $condition['ids'] = ['id', 'in', $ids];
            }
        }
        if (!empty($params['where'])) {
            if (empty($model)) {
                $_this->where($params['where']);
            }
        }
        // 设置limit查询
        if (!empty($params['limit'])) {
            $limitArr = $this->strToArr($params['limit']);
            $limit    = [];
            foreach ($limitArr as $value) {
                $limit[] = intval($value);
            }
            if (count($limit) == 1) {
                if (empty($model)) {
                    $_this->limit($limit[0]);
                } else {
                    $condition['limit'] = $limit[0];
                }
            } elseif (count($limit) == 2) {
                if (empty($model)) {
                    $_this->limit($limit[0], $limit[1]);
                } else {
                    $condition['limit'] = $limit[0] . ',' . $limit[1];
                }
            }
        }
        // 设置分页
        if (!empty($params['page'])) {
            $pageArr = $this->strToArr($params['page']);
            $page    = [];
            foreach ($pageArr as $value) {
                $page[] = intval($value);
            }
            if (count($page) == 1) {
                if (empty($model)) {
                    $_this->page($page[0]);
                } else {
                    $condition['page'] = $page[0];
                }
            } elseif (count($page) == 2) {
                if (empty($model)) {
                    $_this->page($page[0], $page[1]);
                } else {
                    $condition['page'] = $page[0] . ',' . $page[1];
                }
            }
        }
        //设置排序
        if (!empty($params['order'])) {
            $order = $this->strToArr($params['order']);
            foreach ($order as $key => $value) {
                $upDwn      = substr($value, 0, 1);
                $orderType  = $upDwn == '-' ? 'desc' : 'asc';
                $orderField = substr($value, 1);
                if (!empty($whiteParams)) {
                    if (in_array($orderField, $whiteParams)) {
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
     * 设置链式查询
     * @access public
     * @param array $params 链式查询条件
     * @param model $model 模型
     * @return $this
     */
    public function setParamsQuery($params, $model = '')
    {
        if (!empty($model)) {
            $_this = $model;
        } else {
            $_this = $this;
        }
        $_this->alias('articles');
        if (!empty($params['field'])) {
            $_this->field($params['field']);
        }
        if (!empty($params['ids'])) {
            $_this->where('articles.id', $params['ids'][1], $params['ids'][2]);
        }
        if (!empty($params['limit'])) {
            $_this->limit($params['limit']);
        }
        if (!empty($params['page'])) {
            $_this->page($params['page']);
        }
        if (!empty($params['order'])) {
            $_this->order($params['order']);
        }
        return $_this;
    }

    /**
     * 是否允许关联
     * @access public
     * @param string $relationName 模型关联方法名
     * @return boolean
     */
    public function isWhite($relationName)
    {
        if (!is_string($relationName)) {
            return false;
        }
        $name = Loader::parseName($relationName, 1, false);
        if (in_array($name, $this->relationFilter)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 懒人函数
     * @access public
     * @param string $value 字符串
     * @return array
     */
    public function strToArr($string)
    {
        return is_string($string) ? explode(',', $string) : $string;
    }
}