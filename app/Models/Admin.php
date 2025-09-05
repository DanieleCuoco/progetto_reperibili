<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    //protected $table = 'admin';

    protected $fillable = [
        'username',
        'password',
        'name',
        'email'
    ];

    protected $hidden = [
        'password',
    ];
}

