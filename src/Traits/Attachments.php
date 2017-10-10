<?php

namespace Perevorot\LaravelOctober\Traits;

use Perevorot\LaravelOctober\Models\SystemFile;

trait Attachments
{
    public function __call($mutator, $attributes)
    {
        if($this->isAttachmentMutator($mutator)) {
            return $this->{ends_with($mutator, 's') ? 'hasMany' : 'hasOne'}('App\Models\SystemFile', 'attachment_id')
                    ->where('is_public', 1)
                    ->where('attachment_type', $this->getBackendModel($mutator))
                    ->where('field', $mutator);
        }

        return parent::__call($mutator, $attributes);
    }

    public function __get($mutator)
    {
        if($this->isAttachmentMutator($mutator) && !array_key_exists($mutator, $this->relations)) {
            return SystemFile::where('field', $mutator)->where('attachment_id', $this->id)->where('attachment_type', $this->getBackendModel($mutator))->where('is_public', 1)->orderBy('sort_order', 'ASC')->{ends_with($mutator, 's') ? 'get':'first'}();
        }

        return parent::__get($mutator);
    }

    public function isAttachmentMutator($mutator)
    {
        return !empty($this->attachments) && in_array($mutator, $this->attachments) && !empty($this->getBackendModel($mutator));
    }

    private function getBackendModel($mutator)
    {
        if(!empty($this->backendModel)){
            return $this->backendModel;
        }

        return false;
    }
}
