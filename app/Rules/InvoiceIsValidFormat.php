<?php

namespace App\Rules;

use App\Services\TransactionService;
use Illuminate\Contracts\Validation\Rule;

class InvoiceIsValidFormat implements Rule
{
    private $transactionService;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->transactionService = new TransactionService();
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
        return $this->transactionService->isValid($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Lightning invoice is invalid format.';
    }
}
