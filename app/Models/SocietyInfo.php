<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class SocietyInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'registration_no',
        'address',
        'phone',
        'email',
        'established_date',
        'description',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_by_user_id = Auth::id();
                $model->updated_by_user_id = Auth::id();
            }
        });

        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_by_user_id = Auth::id();
            }
        });
    }
}
