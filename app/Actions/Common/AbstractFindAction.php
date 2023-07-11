<?php

namespace App\Actions\Common;

use App\Traits\Common\HasApiResource;
use App\Traits\Common\NewQueryTrait;

abstract class AbstractFindAction
{
    use NewQueryTrait, HasApiResource;

    /**
     * @param $primaryKey
     * @param array|string[] $columns
     * @return BaseModel|null
     */
    public function find($primaryKey, array $columns = ['*']): ?BaseModel
    {
        return $this->getQuery()->find($primaryKey, $columns);
    }

    /**
     * @param $primaryKey
     * @param array|string[] $columns
     * @return BaseModel
     */
    public function findOrFail($primaryKey, array $columns = ['*']): BaseModel
    {
        return $this->getQuery()->findOrFail($primaryKey, $columns);
    }

    /**
     * @param $primaryKey
     * @param array|string[] $columns
     * @return BaseModel
     */
    public function findOrNew($primaryKey, array $columns = ['*']): BaseModel
    {
        return $this->getQuery()->findOrNew($primaryKey, $columns);
    }

    /**
     * @param BaseModel $model
     * @return BaseModel
     */
    public function findByModel(BaseModel $model): BaseModel
    {
        return $model->applyQueryBuilder();
    }
}