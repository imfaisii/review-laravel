<?php

namespace App\Actions\Common;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\Filters\Filter;

class BaseRelationshipFilter implements Filter
{
    /**
     * @var bool
     */
    private bool $nestedRelationship;

    /**
     * @param string $relationship
     * @param string $column
     * @param bool $exact
     */
    public function __construct(
        private string $relationship,
        private readonly string $column,
        private readonly bool $exact
    ) {
        $this->nestedRelationship = str_contains($this->relationship, '.');
        if (!$this->nestedRelationship) {
            $this->relationship = Str::camel($this->relationship);
        }
    }

    /**
     * @param Builder $query
     * @param $value
     * @param string $property
     * @return void
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     * @phpcs:disable VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        if ($this->nestedRelationship) {
            $relationships = explode('.', $this->relationship);
            $this->digRelationship($query, $relationships, 0, $value);
        } else {
            $this->applyRelationshipFilter($query, $this->relationship, $value);
        }
    }

    /**
     * @param Builder $query
     * @param array $relationships
     * @param int $position
     * @param $value
     * @return void
     */
    private function digRelationship(Builder $query, array $relationships, int $position, $value): void
    {
        $relationship = Str::camel($relationships[$position]);
        if ($position === count($relationships) - 1) {
            $this->applyRelationshipFilter($query, $relationship, $value);
        } else {
            $query->whereHas($relationship, function (Builder $query) use ($relationships, $position, $value) {
                $this->digRelationship($query, $relationships, $position + 1, $value);
            });
        }
    }

    /**
     * @param Builder $query
     * @param string $relationship
     * @param $value
     * @return void
     */
    private function applyRelationshipFilter(Builder $query, string $relationship, $value): void
    {
        $query->whereHas($relationship, function (Builder $query) use ($value) {
            if ($this->exact) {
                $query->where($this->column, $value);
            } else {
                $query->where($this->column, 'LIKE', "%$value%");
            }
        });
    }
}
