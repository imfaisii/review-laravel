<?php

namespace App\Actions\Common;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\AllowedFilter;

class BaseQueryBuilder extends \Spatie\QueryBuilder\QueryBuilder
{
    /**
     * @var array
     */
    protected array $orFilters = [];

    /**
     * @return void
     */
    protected function addFiltersToQuery()
    {
        if ($this->isFilterUsingOr()) {
            $this->where(function (Builder $query) {
                if ($orFilters = $this->request->input('or_filters')) {
                    $this->orFilters = explode(',', $orFilters);
                }
                $this->processFilters($query, true);
            });
        } else {
            $this->processFilters();
        }
    }

    /**
     * @return void
     */
    protected function processFilters(Builder $query = null, bool $usingOr = false)
    {
        $this->allowedFilters->each(function (AllowedFilter $filter) use ($query, $usingOr) {
            if ($this->isFilterRequested($filter)) {
                $value = $this->request->filters()->get($filter->getName());

                if ($usingOr && $this->isFilterAnOr($filter)) {
                    $query->orWhere(function (Builder $query) use ($filter, $value) {
                        $filter->filter(new self($query), $value);
                    });
                } else {
                    $filter->filter($this, $value);
                }

                return;
            }

            if ($filter->hasDefault()) {
                if ($usingOr && $this->isFilterAnOr($filter)) {
                    $query->orWhere(function (Builder $query) use ($filter) {
                        $filter->filter(new self($query), $filter->getDefault());
                    });
                } else {
                    $filter->filter($this, $filter->getDefault());
                }
            }
        });
    }

    /**
     * @return bool
     */
    protected function isFilterUsingOr(): bool
    {
        return strtoupper($this->request->input('filter_operation')) === 'OR' ||
            !!$this->request->input('or_filters');
    }

    /**
     * @param AllowedFilter $filter
     * @return bool
     */
    protected function isFilterAnOr(AllowedFilter $filter): bool
    {
        if (count($this->orFilters) === 0) {
            return true;
        }
        return in_array($filter->getInternalName(), $this->orFilters);
    }
}
