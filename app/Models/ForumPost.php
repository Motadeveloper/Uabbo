<?php
// app/Models/ForumPost.php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ForumPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'type', 'title', 'content', 'thumbnail_url', 'room_data', 'description'
    ];

    protected $casts = [
        'room_data' => 'array', // Armazena dados JSON da API do Habbo para promoções de quarto
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}
