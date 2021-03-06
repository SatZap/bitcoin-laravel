<?php

namespace App\Listeners;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Auth\Events\Verified;


class CreditWelcomeSats
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        $event->user->transactions()->create([
            'type' => TransactionType::Credit,
            'description' => 'Welcome sat bonus for email verification.',
            'amount' => env('CREDIT_VERIFY_EMAIL'),
            'status' => TransactionStatus::Final,
        ]);
    }
}
