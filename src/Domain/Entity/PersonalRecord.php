<?php

namespace App\Domain\Entity;

use Illuminate\Database\Eloquent\Model;

class PersonalRecord extends Model
{
    protected $table = 'personal_record';
    public $timestamps = false;

    public $columns = [
        'id',
        'user_id',
        'movement_id',
        'value',
        'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function movement()
    {
        return $this->belongsTo(Movement::class);
    }
}