<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassroomUser extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'credit',
        'classroom_id',
        'user_id',
    ];

    protected $table = 'classroom_user';

    public function classroom()
    {
        return $this->belongsTo(Classroom::class)->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payments()
    {
        return $this->belongsToMany(Payment::class)->as('studentPayment')->withPivot('new_credit')->withTimestamps();
    }
}
