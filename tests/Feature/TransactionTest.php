<?php

namespace Tests\Feature;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TransactionTest extends TestCase
{
    use RefreshDatabase;

    public $lightningInvoice;

    public function setUp(): void
    {
        parent::setUp();
        // This invoice must be for 25 sats.
        $this->lightningInvoice = 'lnbc250n1p3gantqpp5l6p5uk6zu9gzme4kzhga9jrh5htch9rftyxx9yhndehgqqyu72wqdqqcqzzgxqyz5vqrzjqw8c7yfutqqy3kz8662fxutjvef7q2ujsxtt45csu0k688lkzu3ld4dp8qw7jf0qqvqqqqryqqqqthqqpysp5cy3q3rg23pzzv5rythl9qxgua9276ml0cv6mwhadmgu702m6nvzs9qypqsqsqhq0gecqdgrna4qd5j3hg0g9c839cvfv0fr4lz4kupdfr36v0drw0fvk6u8k4xf2lqj7l7vakuhj9y6nqypaqw2euwg5y5uhpeewrcp0gadzd';
    }

    public function test_user_cannot_withdraw_more_than_available_balance(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);  

        $user->transactions()->create([
            'amount' => 25,
            'description' => 'test',
            'status' => TransactionStatus::Final,
            'type' => TransactionType::Credit,
        ]);

        $this
            ->actingAs($user)
            ->post(route('withdrawal.store'), [
                'amount' => 50,
                'lightningInvoice' => $this->lightningInvoice,
            ])->assertSessionHasErrors(['amount']);
    }

    public function test_lightning_invoice_must_match_withdrawal_amount(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);  

        $user->transactions()->create([
            'amount' => 25,
            'description' => 'test',
            'status' => TransactionStatus::Final,
            'type' => TransactionType::Credit,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('withdrawal.store'), [
                'amount' => 20, // Decoded invoice amount should be 25.
                'lightningInvoice' => $this->lightningInvoice,
            ])->assertSessionHasErrors(['lightningInvoice']);
    }

    public function test_lightning_invoice_is_valid_bolt11_format(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);  

        $user->transactions()->create([
            'amount' => 25,
            'description' => 'test',
            'status' => TransactionStatus::Final,
            'type' => TransactionType::Credit,
        ]);

        $this
            ->actingAs($user)
            ->post(route('withdrawal.store'), [
                'amount' => 25,
                'lightningInvoice' => 'invalidinasldkfajlskdf',
            ])->assertSessionHasErrors(['lightningInvoice']);
    }

    public function test_user_can_receive_lightning_payment(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => Carbon::now(),
        ]);  

        $user->transactions()->create([
            'amount' => 25,
            'description' => 'test',
            'status' => TransactionStatus::Final,
            'type' => TransactionType::Credit,
        ]);

        $this
            ->actingAs($user)
            ->post(route('withdrawal.store'), [
                'amount' => 25,
                'lightningInvoice' => $this->lightningInvoice,
            ]);

        $this->assertDatabaseHas('transactions', [
            'type' => TransactionType::Debit,
        ]);
    }
}
