<?php

namespace App\Domain\Entity;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    protected $table = 'user';
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