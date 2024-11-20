<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos
    protected $fillable = ['content', 'user_id', 'topic_id', 'parent_id'];

    /**
     * Relacionamento com o autor do comentário (usuário).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com respostas encadeadas.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->with('replies', 'user') // Carregar recursivamente respostas e usuários
                    ->orderBy('created_at', 'asc'); // Ordenar respostas por data de criação
    }

    /**
     * Relacionamento com o comentário pai.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    /**
     * Relacionamento com o tópico.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * Verifica se o comentário é uma resposta encadeada.
     *
     * @return bool
     */
    public function isReply()
    {
        return $this->parent_id !== null;
    }

    /**
     * Escopo para buscar comentários principais de um tópico (sem pai).
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMainComments($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Escopo para buscar respostas de um comentário específico.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $parentId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRepliesOf($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Carrega todas as respostas recursivamente em formato de árvore.
     *
     * @return array
     */
    public function getRepliesTree()
    {
        return $this->replies->map(function ($reply) {
            return [
                'id' => $reply->id,
                'content' => $reply->content,
                'user' => $reply->user,
                'created_at' => $reply->created_at,
                'replies' => $reply->getRepliesTree(), // Recursivamente carregar respostas
            ];
        })->toArray();
    }
}
