<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Database\Factories\SymbolFactory;

class Symbol extends Model
{
    use HasUuids, HasFactory;

    /**
     * Disable auto increment because of UUIDs.
     *
     * @var bool
     */
    public $incrementing = false;

    protected $fillable = [
        'id',
        'c_name',
        's_name',
        'symbol',
        'f_status',
        'm_category',
        'lot_size',
        'is_test',
        'updated_at'
    ];


    /**
     * Create a new factory instance for the model.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    protected static function newFactory()
    {
        return SymbolFactory::new();
    }
}
