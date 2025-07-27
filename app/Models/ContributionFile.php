<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ContributionFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'contribution_id',
        'file_path',
        'original_name',
        'file_size',
    ];

    public function contribution()
    {
        return $this->belongsTo(Contribution::class);
    }
}
