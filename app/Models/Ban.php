<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Ban extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'banned_until', 'reason'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Verifica se o banimento ainda é válido
    public function isActive()
    {
        return is_null($this->banned_until) || Carbon::now()->lessThan($this->banned_until);
    }
}
