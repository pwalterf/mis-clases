<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime:d/m/Y',
    ];

    public function classroomUsers()
    {
        //return $this->hasMany(ClassroomUser::class)->with('user')->withTrashed();
        return $this->hasMany(ClassroomUser::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->latest();
    }

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::deleting(function ($classroom) {
            $classroom->classroomUsers()->delete();
        });
    }
}
