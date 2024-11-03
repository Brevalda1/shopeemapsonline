<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';

    protected $fillable = [
        'no_telp',
        'password',
        'nama',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $primaryKey = 'id_pengguna';
}
