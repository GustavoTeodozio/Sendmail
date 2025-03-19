<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class SaveEmail extends Model
{
    use HasFactory;

    protected $table = 'saveemail';

    protected $fillable = [
        'user_id',
        'email',
        'subject',
        'message',
        'created_at',
        'updated_at',
    ];
}
