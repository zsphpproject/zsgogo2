<?php

namespace Zsgogo\model;

use App\common\constant\Common;
use Hyperf\DbConnection\Model\Model;
use Hyperf\Paginator\Paginator;
use Hyperf\Utils\Collection;

class BaseModel extends Model {

    /**
     * 返回list
     * @param array $param
     * @param array $where
     * @param array $allowFields
     * @param array $with
     * @return \Hyperf\Database\Model\Collection|array
     */
    public function getList(array $param,array $where, array $allowFields,array $with = []): \Hyperf\Database\Model\Collection|array {
        $param["sort"] = $param["sort"] ?? "";
        $order = Common::getSort($param["sort"]) . "," . $this->primaryKey . " desc";
        return self::where($where)
            ->with($with)
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