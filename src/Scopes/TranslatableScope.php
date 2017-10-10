<?php

namespace Perevorot\LaravelOctober\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Localization;

class TranslatableScope implements Scope
{
    /**
     * Apply translatable scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        if (!$model->ignoreTranslated && !empty($model->getPrimatyTranslatableMutators())) {
            if (Localization::getDefaultLocale()!=Localization::getCurrentLocale()) {
                $builder->select($model->table.'.*');
                $builder->join($model->translationIndexesTable, $model->table.'.id', '=', $model->translationIndexesTable.'.model_id');

                $builder->where(function ($q) use ($model) {
                    $q->where($model->translationIndexesTable.'.model_type', $model->backendModel);
                    $q->where($model->translationIndexesTable.'.locale', Localization::getCurrentLocale());

                    foreach ($model->getPrimatyTranslatableMutators() as $column) {
                        $q->where(function ($q) use ($column) {
                            $q->where('item', $column);
                            $q->where('value', '!=', '');
                        });
                    }
                });
            } else {
                foreach ($model->getPrimatyTranslatableMutators() as $column) {
                    $builder->where($column, '!=', '');
                    $builder->whereNotNull($column);
                }
            }
        }
    }
}
