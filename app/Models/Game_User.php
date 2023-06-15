<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game_User extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'room_pin',
        'score',
        'session_id'
    ];
}
