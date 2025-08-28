<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tenant; // central tenants table
use Stancl\Tenancy\Tenancy;

class TenantLoginUsersController extends Controller
{
    public function store(Request $request)
    {
        // 1️⃣ Validate the input
        $request->validate([
            'tenant_code' => 'required|string|exists:tenants,code',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // 2️⃣ Fetch the tenant from central table
        $tenant = Tenant::where('code', $request->tenant_code)->firstOrFail();

        // 3️⃣ Initialize tenancy for this tenant
        tenancy()->initialize($tenant);

        // 4️⃣ Attempt login in tenant database
        if (Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {
            $user = Auth::user();

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'tenant_domain' => $tenant->domain,
            ], 200);
        }

        // 5️⃣ Invalid credentials
        return response()->json([
            'error' => 'Invalid credentials'
        ], 401);
    }
}
