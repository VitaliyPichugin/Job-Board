<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobVacancy extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'job_tag', 'job_id', 'tag_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function responses()
    {
        return $this->hasMany(JobVacancyResponse::class, 'job_id', 'id');
    }

    public function likes()
    {
        return $this->morphToMany(UserLike::class, 'likeable');
    }
}
