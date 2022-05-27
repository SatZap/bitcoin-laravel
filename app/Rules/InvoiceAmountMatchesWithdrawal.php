<?php

namespace App\Rules;

use App\Services\TransactionService;
use Illuminate\Contracts\Validation\Rule;

class InvoiceAmountMatchesWithdrawal implements Rule
{
    private $transactionService;
    public $amount;
    public $lightningInvoice;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(int $amount, string $lightningInvoice)
    {
        $this->transactionService = new TransactionService();
        $this->amount = $amount;
        $this->lightningInvoice = $lightningInvoice;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($this->transactionService->getSatoshis($this->lightningInvoice) === $this->amount);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Withdrawal amount must match invoice amount. ';
    }
}
