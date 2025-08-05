<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    public function vendingMachine()
    {
        return $this->belongsTo(VendingMachine::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
