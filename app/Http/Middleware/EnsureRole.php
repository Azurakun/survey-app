<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        $userRole = strtoupper($user->role ?? 'ADMIN');
        $allowedRoles = array_map('strtoupper', $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Akses ditolak: Akun Anda memiliki peran Pengamat/Viewer (Hanya Baca). Anda tidak memiliki izin untuk melakukan tindakan ini.',
                ], 403);
            }

            return redirect()->route('admin.surveys.index')
                ->with('error', 'Akses ditolak: Akun Anda memiliki peran Pengamat/Viewer (Hanya Baca). Anda tidak memiliki izin untuk mengubah atau menghapus data.');
        }

        return $next($request);
    }
}
