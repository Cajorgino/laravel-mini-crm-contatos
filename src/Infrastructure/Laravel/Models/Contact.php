<?php

declare(strict_types=1);

namespace Infrastructure\Laravel\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $table = 'contacts';

    protected $fillable = [
        'name',
        'email',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'processed_at' => 'immutable_datetime',
            'created_at' => 'immutable_datetime',
            'updated_at' => 'immutable_datetime',
            'deleted_at' => 'immutable_datetime',
        ];
    }
}
