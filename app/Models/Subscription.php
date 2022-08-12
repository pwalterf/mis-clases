<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'price_hr',
        'started_at',
        'classroom_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'price_hr' => 'decimal:2',
        'started_at' => 'datetime:d/m/Y',
        'created_at' => 'datetime:d/m/Y',
    ];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }
}
