<?php

namespace App\Domain\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movement extends Model
{
    protected $table = 'movement';
    public $timestamps = false;

    public $columns = [
        'id',
        'name'
    ];

    public function personalRecords(): HasMany
    {
        return $this->hasMany(PersonalRecord::class);
    }
}