<?php

namespace Zsgogo\model;

use Zsgogo\constant\Common;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Paginator\Paginator;
use Hyperf\Utils\Collection;

class BaseModel extends Model {

    /**
     * 返回list
     * @param array $param
     * @param array $where
     * @param array $allowFields
     * @return \Hyperf\Database\Model\Collection|array
     */
    public function getList(array $param,array $where, array $allowFields): \Hyperf\Database\Model\Collection|array {
        $order = Common::getSort($param["sort"]) . "," . $this->primaryKey . " desc";
        return self::where($where)
            ->orderByRaw($order)
            ->select($allowFields)
            ->get();
    }

    /**
     * 返回分页
     * @param \Hyperf\Database\Model\Collection|array $voteList
     * @param array $param
     * @return Paginator
     */
    public function getPageList(\Hyperf\Database\Model\Collection|array $voteList,array $param): Paginator {
        $collection = new Collection($voteList->toArray());
        $list = $collection->forPage($param["page"], $param["size"])->toArray();
        return new Paginator($list,$param["size"],$param["page"]);
    }
}