<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailChange extends Model
{
    protected $fillable = ['user_id', 'new_email', 'token'];
}
