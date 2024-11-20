<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckLevel
{
    public function handle($request, Closure $next, $level)
    {
        $user = Auth::user();

        if (!$user || !$user->hasLevel($level)) {
            abort(403, 'Você não tem permissão para acessar esta página.');
        }

        if ($user->isBanned()) {
            $ban = $user->ban; // Presume-se que haja um relacionamento ou método que retorne os detalhes do banimento
            $remainingTime = $ban->getRemainingTime(); // Presume-se que esse método calcule o tempo restante do banimento
            $reason = $ban->reason ?? 'Não especificado';
            
            $banMessage = $ban->isPermanent 
                ? "Sua conta está banida permanentemente. Motivo: $reason."
                : "Sua conta está banida por $remainingTime. Motivo: $reason.";

            abort(403, $banMessage);
        }

        return $next($request);
    }
}
