<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'income',
        'payment_date',
        'comment',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'payment_date' => 'datetime:d/m/Y',
        'created_at' => 'datetime:d/m/Y',
    ];

    public function classroomUsers()
    {
        return $this->belongsToMany(ClassroomUser::class)->as('studentPayment')->withPivot('new_credit')->withTimestamps();
    }
}
