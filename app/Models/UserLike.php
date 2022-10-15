<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLike extends Model
{
    use HasFactory;

    protected $table = 'users';

    public function likeUser()
    {
        return $this->morphedByMany(User::class, 'likeable');
    }

    public function likeJob()
    {
        return $this->morphedByMany(JobVacancy::class, 'likeable');
    }
}
