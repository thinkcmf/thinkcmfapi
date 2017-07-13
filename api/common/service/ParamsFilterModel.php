<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: pl125 <xskjs888@163.com>
// +----------------------------------------------------------------------

namespace api\common\service;

use think\Model;

class ParamsFilterModel extends Model
{
	protected $whitelist = [];
	protected $whereFilter = [
					'<>',
					'>',
					'>=',
					'<',
					'<=',
					'like',
					'not like',
					'between',
					'not between',
					'in',
					'not in',
					'null',
					'not null',
					'exists',
					'not exists',
					'> time',
					'< time',
					'>= time',
					'<= time',
					'between time',
					'notbetween time'
				]
    public setCondition($params)
    {
    	$whitelist = $this->$whitelist;
    	if (!empty($whitelist) && is_string($whitelist)) {
    		$whiteParams = explode(',',$whitelist['field']);
    	}

    	if (!empty($params['field'])) {
    		if (!empty($whitelist['field'])) {
    			if (is_string($params['field'])) {
    				$filterParams = explode(',',$params['field']);
    			}
                $mixedField = array_intersect($filterParams,$whiteParams);
    		}
    		if (!empty($mixedField)) {
                $this->field($mixedField);
            }
    	}

    	if (!empty($params['ids'])) {
    		$ids = $params['ids'];
    		if (is_string($ids)) {
    			$ids = explode(',',$params['ids']);
    		}
    		foreach ($ids as $key => $value) {
				$ids[$Key] = intval($value);
			}
			$this->where('id','in',$ids);
    	}

    	if (!empty($params['where'])) {
    		foreach ($params['where'] as $key => $value) {
    			$whereField = explode(',',$value);
    			$whereFieldLength = count($whereField);
    			if (!empty($whitelist)) {
    				if (in_array($key,$whitelist)) {
    					switch ($whereFieldLength) {
	    					case 1:
	    						$where[$key] = $whereField[0];
	    						break;
	    					case 2:
	    						if (in_array($whereField[0],$this->whereFilter)) {
	    							if (strpos($whereField[0], 'between') !== false) {
	    								if (is_string($whereField[1])) {
			    							$whereField[1] = explode(',',$whereField[1]);
			    						}
			    						if (count($whereField[1]) == 2) {
			    							$where[$Key] = [$whereField[0],[$whereField[1][0],$whereField[1][0]]];
			    						}

	    							} else {
	    								$where[$key] = [$whereField[0],$whereField[1]];
	    							}
	    						}
    							break;
    					}
	    			}
    			} else {
    				switch ($whereFieldLength) {
    					case 1:
    						$where[$key] = $whereField[0];
    						break;
    					case 2:
    						if (in_array($whereField[0],$this->whereFilter)) {
    							if (strpos($whereField[0], 'between') !== false) {
    								if (is_string($whereField[1])) {
		    							$whereField[1] = explode(',',$whereField[1]);
		    						}
		    						if (count($whereField[1]) == 2) {
		    							$where[$Key] = [$whereField[0],[$whereField[1][0],$whereField[1][0]]];
		    						}

    							} else {
    								$where[$key] = [$whereField[0],$whereField[1]];
    							}
    						}
    						break;
    				}
    			}
    		}
    	}

    	if (!empty($params['limit']) && is_string($params['limit'])) {
    		$limit = explode(',',$limit);
    		foreach ($limit as $key => $value) {
    			$limit[$key] = intval($value);
    		}

    		if (!empty($limit[0])) {
    			if (count($limit) == 1) {
    				$this->limit($limit[0]);
    			} else {
    				$this->limit($limit[0],$limit[1]);
    			}
    		}
    	}

    	if (!empty($params['page']) && is_string($params['page'])) {
    		$page = explode(',',$params['page']);
    		foreach ($page as $key => $value) {
    			$page[$key] = intval($value);
    		}

    		if (!empty($page[0])) {
    			if (count($page) == 1) {
    				$this->page($page[0]);
    			} else {
    				$this->page($page[0],$page[1]);
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
    					$order[$orderField] = $orderType;
    				}
    			} else {
    				$order[$orderField] = $orderType;
    			}
    		}
    		if (!empty($order)) {
				$this->order($order);
			}
    	}
    }
}
