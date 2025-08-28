<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant; // central tenants table
use App\Models\Tenant\Branding;
use Stancl\Tenancy\Tenancy;

class TenantLoginUsersController extends Controller
{
    public function store(Request $request)
    {
        // 1️⃣ Validate the input
        $request->validate([
            // 'tenant_code' => 'required|string|exists:tenants,code',
            'tenant_code' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2️⃣ Fetch the tenant from central table
        // $tenant = Tenant::where('code', $request->tenant_code)->firstOrFail();

        $tenant = Tenant::where('data->code', $request->tenant_code)->first();

        // 3️⃣ Initialize tenancy for this tenant
        tenancy()->initialize($tenant);

        // 4️⃣ Attempt login in tenant database
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();
            $brand = Branding::first();

            // 🔑 Generate Passport token
            // $token = $user->createToken('user')->accessToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                "brand" => $brand,
                'tenant_domain' => $tenant->domains->first()->domain ?? null,
                // 'token' => $token,   // return token here
            ], 200);
        }

        // 5️⃣ Invalid credentials
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }
}
