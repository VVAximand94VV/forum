<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BannedUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = false;
    protected $table = 'banned_users';

    public function users(){
        return $this->hasMany(User::class, 'userId', 'id');
    }
}
