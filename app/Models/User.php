<?php

namespace App\Models;

use App\Enums\TransactionType;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function getBalance()
    {
        $credits = $this->transactions()->where('type', TransactionType::Credit)->sum('amount');
        $debits = $this->transactions()->where('type', TransactionType::Debit)->sum('amount');
        
        return $credits - $debits;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
