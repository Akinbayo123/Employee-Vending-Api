<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    public function classification()
    {
        return $this->belongsTo(Classification::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    protected $guarded = [];
}
