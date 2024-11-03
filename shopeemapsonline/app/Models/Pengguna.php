<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengguna extends Model
{
    protected $table = 'pengguna';

    protected $fillable = [
        'no_telp',
        'nama',
        'password',
        'role'
    ];

    protected $hidden = [
        'password'
    ];
}
