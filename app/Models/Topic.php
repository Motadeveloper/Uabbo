<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['content', 'user_id'];

    /**
     * Relacionamento com o usuário que criou o tópico.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com os comentários do tópico.
     * Apenas os comentários principais (sem parent_id).
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Retorna todos os comentários (incluindo respostas) organizados.
     */
    public function allComments()
    {
        return $this->hasMany(Comment::class)
            ->with('user', 'replies.user') // Inclui os usuários e as respostas
            ->orderBy('created_at', 'asc');
    }

    /**
     * Atualiza a data de atualização do tópico sempre que um comentário é adicionado.
     */
    protected $touches = ['comments'];

    /**
     * Contador total de comentários, incluindo respostas.
     */
    public function totalCommentsCount()
    {
        return $this->comments()->withCount('replies')->get()->sum(fn($comment) => 1 + $comment->replies->count());
    }
}
