<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant;
use App\Models\Tenant\Branding;
use Illuminate\Support\Facades\Response;
use Laravel\Passport\ClientRepository;
use Stancl\Tenancy\Tenancy;
use Symfony\Component\HttpFoundation\JsonResponse;

class TenantLoginUsersController extends Controller
{
    public function store(Request $request)
    {
        // Validate the input
        $request->validate([
            'tenant_code' => 'required|string|exists:tenants,data->code',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $tenant = Tenant::where('data->code', $request->tenant_code)->first();
        tenancy()->initialize($tenant);

        //Attempt login in tenant database
        if (
            Auth::attempt([
                'email' => $request->email,
                'password' => $request->password
            ])
        ) {
            $user = Auth::user();
            $brand = Branding::first();
            $token = $user->createToken('user');
            $data = [
                'user' => new UserResource($user),
                "brand" => $brand,
                'tenant_domain' => $tenant->domains->first()->domain ?? null,
                'token' => $token
            ];
            return Response::v1(JsonResponse::HTTP_OK, "Login successful", $data);
        }

        // 5️⃣ Invalid credentials
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }
}