<?php

namespace App\Http\Controllers\Api;

use App\Models\Slot;
use App\Models\Employee;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EligibleProductResource;
use App\Http\Resources\PurchaseResponseResource;

class VendingApiController extends Controller
{
    public function getEligibleProducts(Request $request)
    {
        $cardNumber = $request->query('card_number');

        if (!$cardNumber) {
            return response()->json(['message' => 'card_number is required'], 422);
        }

        $employee = Employee::with('classification')->where('card_number', $cardNumber)->first();

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        if ($employee->status !== 'active') {
            return response()->json(['message' => 'Employee card is inactive'], 403);
        }

        // Attach a fake relationship for the resource to access
        $employee->setRelation('availableSlots', Slot::with('vendingMachine')->get());

        return new EligibleProductResource($employee);
    }


    public function purchaseSlot(Request $request)
    {
        $request->validate([
            'card_number' => 'required|exists:employees,card_number',
            'slot_id' => 'required|exists:slots,id',
        ]);

        $employee = Employee::with('classification')->where('card_number', $request->card_number)->first();
        $slot = Slot::with('vendingMachine')->find($request->slot_id);

        if ($error = $this->validatePurchase($employee, $slot)) {
            return $error;
        }

        $this->recordTransaction($employee, $slot);

        return new PurchaseResponseResource($slot);
    }

    private function validatePurchase(Employee $employee, Slot $slot)
    {
        if ($employee->status !== 'active') {
            return response()->json(['message' => 'Card is inactive'], 403);
        }

        $limitField = 'daily_' . strtolower($slot->category) . '_limit';
        $dailyLimit = $employee->classification->{$limitField} ?? 0;

        $todayCount = Transaction::where('employee_id', $employee->id)
            ->whereDate('created_at', now()->toDateString())
            ->whereHas('slot', function ($query) use ($slot) {
                $query->where('category', $slot->category);
            })
            ->count();

        if ($todayCount >= $dailyLimit) {
            return response()->json([
                'message' => "Daily limit reached for {$slot->category}."
            ], 403);
        }

        if ($employee->classification->daily_point_limit < $slot->price) {
            return response()->json([
                'message' => 'Insufficient balance.'
            ], 403);
        }

        return null;
    }

    private function recordTransaction(Employee $employee, Slot $slot)
    {
        Transaction::create([
            'employee_id' => $employee->id,
            'vending_machine_id' => $slot->vending_machine_id,
            'slot_id' => $slot->id,
            'points_deducted' => $slot->price, // assuming 'price' == points
            'transaction_time' => now(),
            'status' => 'success',
            'failure_reason' => null,
        ]);


        $employee->classification->decrement('daily_point_limit', $slot->price);
    }
}
