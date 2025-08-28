<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class VerifyOTPMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $action): Response
    {
        if(App::environment(['testing', 'local'])) {
            // Skip verification in testing or local environment
            return $next($request);
        }
        $request->validate([
            'token' => ['required', 'string'],
            'code' => ['required', 'digits:6']
        ]);
        try {
            $data = json_decode(Crypt::decryptString($request->token), true);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Invalid token.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (!($user = User::find($data['user'] ?? ''))) {
            return response()->json([
                'message' => 'No user found',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        if ($data['user'] !== $user->id) {
            return response()->json([
                'message' => 'the requested user is invalid.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        if ($data['action'] !== $action) {
            return response()->json([
                'message' => 'the requested operation is invalid.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (Carbon::createFromTimestamp($data['expires_at'])->lessThan(now())) {
            return response()->json([
                'message' => 'Code has expired.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        if (
            !hash_equals((string) $data['code'], (string) $request->code)
        ) {
            return response()->json([
                'message' => 'You provided an invalid code.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $request->merge($data['data'] ?? []);
        return $next($request);
    }
}
