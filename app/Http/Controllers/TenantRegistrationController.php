<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class TenantRegistrationController extends Controller
{
    /**
     * Show the tenant registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // 1. Validate the request data
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // 2. Create the Tenant
        $tenant = Tenant::create([
            'id' => str_replace(' ', '', strtolower($validated['company_name'])), // Simple way to generate an ID
            'name' => $validated['company_name'],
            // Add other tenant data if necessary, like plan
        ]);

        // 3. Run a callback in the tenant's context to create the user
        $user = null;
        $tenant->run(function () use ($validated, &$user) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);
        });

        // 4. Log the user in
        auth()->login($user);

        // 5. Redirect to the tenant's dashboard
        return redirect()->route('dashboard');
    }
}
