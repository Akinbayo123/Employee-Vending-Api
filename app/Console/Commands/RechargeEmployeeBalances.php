<?php

namespace App\Console\Commands;

use App\Models\Employee;
use Illuminate\Console\Command;

class RechargeEmployeeBalances extends Command
{

    protected $signature = 'employees:recharge-balances';
    protected $description = 'Reset employee balances and recharge based on classification limits';

    public function handle(): void
    {
        $this->info('Starting balance recharge...');

        Employee::with('classification')->chunk(100, function ($employees) {
            foreach ($employees as $employee) {
                $limit = $employee->classification->daily_point_limit ?? 0;

                $employee->balance = $limit; // reset and refill
                $employee->save();
            }
        });

        $this->info('Balances successfully recharged.');
    }
}
