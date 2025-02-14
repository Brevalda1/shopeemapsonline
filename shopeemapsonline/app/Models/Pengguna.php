<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengguna extends Authenticatable
{
    use Notifiable;

    protected $table = 'pengguna';
    protected $primaryKey = 'no_telp';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nama',
        'no_telp',
        'password',
        'role',
        'tanggal_exp'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
