<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use Notifiable, HasFactory;

    // Níveis de permissão
    const LEVEL_USER = 1;
    const LEVEL_MODERATOR = 2;
    const LEVEL_STAFF = 3;

    /**
     * Campos que podem ser preenchidos em massa.
     *
     * @var array
     */
    protected $fillable = [
        'name',         // Nickname ou identificador único do usuário
        'password',     // Senha do usuário
        'level',        // Nível de permissão
        'habbo_code',   // Código da missão no Habbo
    ];

    /**
     * Campos ocultos para arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',          // Ocultar a senha
        'remember_token',    // Ocultar token de lembrete
    ];

    /**
     * Verifica se o usuário possui o nível necessário.
     *
     * @param int $level
     * @return bool
     */
    public function hasLevel($level)
    {
        return $this->level >= $level;
    }

    /**
     * Relacionamento: usuário pode ter um registro de banimento.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function ban()
    {
        return $this->hasOne(Ban::class);
    }

    /**
     * Verifica se o usuário está banido.
     *
     * @return bool
     */
    public function isBanned()
    {
        $ban = $this->ban;
        return $ban && $ban->isActive();
    }

    /**
     * Define o hash da senha automaticamente.
     *
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        if ($password) {
            $this->attributes['password'] = bcrypt($password);
        }
    }

    /**
     * Valida se o código do Habbo está presente e corresponde.
     *
     * @param string $code
     * @return bool
     */
    public function validateHabboCode($code)
    {
        return strtolower(trim($this->habbo_code)) === strtolower(trim($code));
    }
}
