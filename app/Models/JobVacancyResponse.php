<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JobVacancyResponse extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function job()
    {
        return $this->belongsTo(JobVacancy::class);
    }

    public function check()
    {
        return $this->belongsTo(JobVacancy::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
