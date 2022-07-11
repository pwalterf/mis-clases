<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'comment',
        'student_page',
        'workbook_page',
        'lesson_date',
        'classroom_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'lesson_date' => 'datetime:d/m/Y',
        'created_at' => 'datetime:d/m/Y',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
