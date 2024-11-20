<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comentario extends Model
{
    use HasFactory;

    protected $fillable = [
        'conteudo',
        'sorteio_id',
        'user_id',
    ];

    // Relação com o modelo Sorteio
    public function sorteio()
    {
        return $this->belongsTo(Sorteio::class);
    }

    // Relação com o modelo User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
