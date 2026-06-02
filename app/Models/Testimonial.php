<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Testimonial extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;

    protected $fillable = ['user_id', 'name', 'position', 'content', 'image', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
