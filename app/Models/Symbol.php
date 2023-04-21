<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Symbol extends Model
{
    use HasUuids;

    /**
     * Disable auto increment because of UUIDs.
     *
     * @var bool
     */
    public $incrementing = false;
}
