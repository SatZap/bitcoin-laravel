<?php

namespace App\Enums;

enum TransactionStatus: string
{
    case Pending = 'pending';
    case Final = 'final';
}