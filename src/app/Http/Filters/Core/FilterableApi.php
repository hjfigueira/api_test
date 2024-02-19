<?php

namespace App\Http\Filters\Core;

use App\Http\Filters\FilterGreaterEqual;
use App\Http\Interfaces\ApiFilterableRepository;
use App\Http\Filters\FilterEquals;
use App\Http\Filters\FilterLesserEqual;
use \Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * @property ApiFilterableRepository $repository
 */
trait FilterableApi
{
    /** @var array|string[] This should be moved to a proper service */
    protected const array AVAILABLE_FILTERS = [
        'equal'     => FilterEquals::class,
        'lessEq'    => FilterLesserEqual::class,
        'greatEq'   => FilterGreaterEqual::class
    ];

    public function applyFilters(Request $request, int $perPage, int $page) : LengthAwarePaginator
    {
        $enabledFilters = $this->getFilters();
        $requestFilters = $request->query('filter');
        $query = $this->repository->getFilterableQuery();


        foreach ($enabledFilters as $field => $operands) {
            $filtersForField = $requestFilters[$field] ?? [];

            $query->where(function ($builder) use ($operands, $filtersForField, $field) {
                return $this->modifyQueryForField($builder, $operands, $field, $filtersForField);
            });
        }
        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    private function modifyQueryForField(
        Builder $builder,
        array $operands,
        string $field,
        array $filtersForField
    ) : Builder
    {

        foreach ($operands as $operand) {
            $value = $filtersForField[$operand] ?? null;
            if ($value == null) {
                continue;
            }

            /** @var ApiFilter $filterInstance */
            $filterInstance = new (self::AVAILABLE_FILTERS[$operand]);
            $builder = $filterInstance->apply($field, $value, $builder);
        }

        return $builder;
    }
}
