<?php

namespace App\Listeners;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreditNewPostSats
{
    public function handle($event)
    {
        $event->post->user->transactions()->create([
            'type' => TransactionType::Credit,
            'description' => 'Sat award for submitting a new post.',
            'amount' => env('CREDIT_NEW_POST'),
            'status' => TransactionStatus::Final,
        ]);
    }
}
