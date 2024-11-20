<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SorteioPremio extends Model
{
    use HasFactory;

    protected $fillable = ['sorteio_id', 'posicao', 'premio_tipo', 'premio_quantidade'];

    public function sorteio()
    {
        return $this->belongsTo(Sorteio::class);
    }
}