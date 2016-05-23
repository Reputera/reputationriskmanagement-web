<?php

namespace App\Entities\Traits;

trait Toggleable
{
    public function toggleTrashed()
    {
        if ($this->trashed()) {
            $this->restore();
        } else {
            $this->delete();
        }
        return $this;
    }
}
