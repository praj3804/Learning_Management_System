<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Batch extends Model
{
    protected $fillable = ['name', 'token'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
