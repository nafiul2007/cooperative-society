<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'mobile_number',
        'nid',
        'date_of_birth',
        'tin',
        'business_share',
        'created_by_user_id',
        'updated_by_user_id',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contributions()
    {
        return $this->hasMany(Contribution::class);
    }
}
