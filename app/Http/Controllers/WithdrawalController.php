<?php

namespace App\Http\Controllers;

use App\Http\Requests\WithdrawalRequest;
use App\Services\TransactionService;
use Illuminate\Http\Request;

class WithdrawalController extends Controller
{
    public function __construct(private TransactionService $transactionService)
    {
    }

    public function store(WithdrawalRequest $request)
    {
        $withdrawal = $this->transactionService->withdraw(
            amount: $request->validated('amount'),
            lightningInvoice: $request->validated('lightningInvoice')
        );

        return ($withdrawal === true)
            ? redirect()->route('dashboard')->withSuccess('Lightning invoice paid!')
            : redirect()->route('dashboard')->withErrors('Something went wrong.');
    }
}
