<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendingMachine extends Model
{
    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
