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

    protected static function booted()
    {
        static::created(function ($employee) {
            $employee->update([
                'balance' => $employee->classification->daily_point_limit ?? 0
            ]);
        });

        static::updated(function ($employee) {
            // Only reset balance if classification changed
            if ($employee->isDirty('classification_id')) {
                $employee->updateQuietly([
                    'balance' => $employee->classification->daily_point_limit ?? 0
                ]);
            }
        });
    }

    protected $guarded = [];
}
