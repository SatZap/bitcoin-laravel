<?php

namespace App\Services;

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
use App\Models\Transaction;
use GuzzleHttp\Client;

class TransactionService 
{ 
    public function withdraw(int $amount, string $lightningInvoice)
    {
        $client = new Client();

        try {
            $response = $client->request('POST', 'https://satzap.io/api/pay', [
                'form_params' => [
                    'amount' => $amount,
                    'lightningInvoice' => $lightningInvoice,
                ],
                'headers' => [
                    'Authorization' => 'Bearer ' . env('SATZAP_API'),
                ],
            ]);

            if ($response->getStatusCode() === 200) {

                auth()->user()->transactions()->create([
                    'type' => TransactionType::Debit,
                    'amount' => $amount,
                    'description' => 'Withdrawal',
                    'status' => TransactionStatus::Final,
                ]);

                return true;
            }
        } catch (\Exception $e) {
            return false;
        }
    }

    public function decodeInvoice(string $invoice)
    {
        $decoder = new \Jorijn\Bitcoin\Bolt11\Encoder\PaymentRequestDecoder();
        $denormalizer = new \Jorijn\Bitcoin\Bolt11\Normalizer\PaymentRequestDenormalizer();

        try {
            return $denormalizer->denormalize($decoder->decode($invoice));
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function getSatoshis(string $invoice): int
    {
        return ($this->isValid($invoice)) 
            ? $this->decodeInvoice($invoice)?->getSatoshis()
            : 0;
    }

    public function isValid(string $invoice): bool
    {
        return (!is_string($this->decodeInvoice($invoice)));
    }
}