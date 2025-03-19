<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Smtp extends Model
{
    use HasFactory;

    protected $table = 'smtp';

    protected $fillable = [
        'user_id',
        'mailer',
        'host',
        'port',
        'username',
        'password',
        'from_address',
        'from_name',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }


    //ajustar aqui

    public function setPasswordattribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    // public function getPasswordattribute()
    // {
    //     return decrypt($this->attributes['password']);
    // }
}
