<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'slug',
        'title_ar',
        'title_en',
        'content_ar',
        'content_en',
        'team_members',
        'features',
        'sections',
    ];

    protected $casts = [
        'team_members' => 'array',
        'features' => 'array',
        'sections' => 'array',
    ];
}
