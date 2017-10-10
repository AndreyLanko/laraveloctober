<?php

namespace Perevorot\LaravelOctober\Classes;

use Illuminate\Database\Eloquent\Model as EloquentModel;

class Model extends EloquentModel
{
    public function hasGetMutator($mutator)
    {
        if (method_exists($this, 'isTranslatableMutator') && $this->isTranslatableMutator($mutator)) {
            return true;
        }

        if (method_exists($this, 'isLongreadMutator') && $this->isLongreadMutator($mutator)) {
            return true;
        }

        return method_exists($this, 'get'.studly_case($mutator).'Attribute');
    }

    public function mutateAttribute($mutator, $value)
    {
        if (method_exists($this, 'isTranslatableMutator') && $this->isTranslatableMutator($mutator)) {
            return $this->translatedValue($mutator, $value);
        }

        if (method_exists($this, 'isLongreadMutator') && $this->isLongreadMutator($mutator)) {
            return $this->longreadValue($mutator, $value);
        }

        return $this->{'get'.studly_case($mutator).'Attribute'}($value);
    }
}
