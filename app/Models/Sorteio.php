<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Sorteio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 
        'title', 
        'description', 
        'premio_detalhes', // JSON para armazenar os prêmios e suas colocações
        'data_sorteio', // Data e hora em que o sorteio foi realizado
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'premio_detalhes' => 'array', // JSON com detalhes dos ganhadores e prêmios
    ];

    // Relacionamento com o usuário que criou o sorteio
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com os usuários participantes do sorteio
    public function participantes()
    {
        return $this->belongsToMany(User::class, 'sorteio_participantes')->withTimestamps();
    }

    // Relacionamento com a tabela de prêmios do sorteio
    public function premios()
    {
        return $this->hasMany(SorteioPremio::class);
    }

    // Relacionamento com o vencedor, se já houver
    public function vencedor()
    {
        return $this->belongsTo(User::class, 'vencedor_id');
    }

    // Formatar a data do sorteio para exibição
    public function getDataSorteioFormatadoAttribute()
    {
        return $this->data_sorteio ? Carbon::parse($this->data_sorteio)->format('d/m/Y H:i') : null;
    }

    public function comentarios()
{
    return $this->hasMany(Comentario::class);
}
}
