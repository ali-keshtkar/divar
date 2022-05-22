<?php

namespace Modules\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\User\Database\factories\UserFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone_number',
        'gender',
    ];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }
}
