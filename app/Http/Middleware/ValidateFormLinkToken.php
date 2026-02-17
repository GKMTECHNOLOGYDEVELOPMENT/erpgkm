<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidateFormLinkToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = (string) $request->query('token', '');

        if (strlen($token) < 30) {
            abort(403, 'Link inválido.');
        }

        $hash = hash('sha256', $token);

        $row = DB::table('form_links')
            ->where('token_hash', $hash)
            ->first();

        if (!$row) {
            abort(403, 'Link inválido.');
        }

        // ✅ Expiración (America/Lima)
        $now = new \DateTime('now', new \DateTimeZone('America/Lima'));
        $exp = new \DateTime($row->expires_at, new \DateTimeZone('America/Lima'));

        if ($now > $exp) {
            abort(403, 'Link expirado.');
        }

        // ✅ Uso único (si lo estás usando)
        if (!empty($row->used_at)) {
            abort(403, 'Link ya utilizado.');
        }

        // ✅ por si lo necesitas en el controller / blade
        $request->attributes->set('form_link_id', $row->id);
        $request->attributes->set('form_link_created_by', $row->created_by);

        return $next($request);
    }
}