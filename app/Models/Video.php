<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = ['title', 'url', 'path'];

    public function progress()
    {
        return $this->hasMany(Progress::class);
    }
}
