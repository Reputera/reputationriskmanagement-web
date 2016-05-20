<?php

namespace App\Entities;

use App\Services\Traits\Enumerable;

class Status
{
    use Enumerable;

    const ENABLED = 'Enabled';
    const EMAIL_NOT_CHANGED = 'Email not changed';
    const DISABLED = 'Disabled';
}
