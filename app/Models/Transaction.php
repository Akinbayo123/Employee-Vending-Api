<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function vendingMachine()
    {
        return $this->belongsTo(VendingMachine::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    protected $casts = [
        'transaction_time' => 'datetime',
    ];
}
