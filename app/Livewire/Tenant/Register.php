<?php

namespace App\Livewire\Tenant;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Register extends Component
{
    #[Rule('required|string|max:255|regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/')]
    public $subdomain = '';

    #[Rule('required|string|max:255')]
    public $company_name = '';

    #[Rule('required|string|max:255')]
    public $name = '';

    #[Rule('required|email|max:255|unique:users,email')]
    public $email = '';

    #[Rule('required|min:8|confirmed')]
    public $password = '';

    public $password_confirmation = '';

    public function register()
    {
        // Validate all inputs
        $validated = $this->validate([
            'subdomain' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'company_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        // Generate slug from subdomain
        $slug = Str::slug($validated['subdomain']);

        // Check if slug is unique
        if (Tenant::where('slug', $slug)->exists()) {
            $this->addError('subdomain', 'This subdomain is already taken. Please choose another.');

            return;
        }

        try {

            DB::beginTransaction();
            // 1. Create the first user (owner/admin) - do this FIRST
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Generate a UUID for the tenant ID
            $tenantId = (string) Str::uuid();

            // 2. Create the Tenant with the user as owner
            DB::table('tenants')->insert([
                'id' => $tenantId,
                'name' => $validated['company_name'],
                'slug' => $slug,
                'owner_id' => $user->id, // Use the newly created user's ID
                'status' => 'active',
                'subscription_plan' => null,
                'stripe_id' => null,
                'pm_type' => null,
                'pm_last_four' => null,
                'trial_ends_at' => null,
                'is_on_trial' => false,
                'is_active' => true,
                'features' => null,
                'member_limit' => 4,
                'storage_limit' => 7,
                'data' => json_encode([]),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Retrieve the created tenant
            $tenant = Tenant::find($tenantId);
            // 3. Attach tenant to the user
            $user->tenant_id = $tenant->id;
            $user->save();

            // create subdomain
            $tenant->domains()->create([
                'domain' => $validated['subdomain'],
            ]);

            // 4. Create a default first store for the new tenant
            $tenant->stores()->create([
                'name' => 'Main Store / Head Office',
            ]);

            // 5. Assign default role (assuming Spatie)
            $user->assignRole('super admin');

            // 6. Fire event and login
            event(new Registered($user));
            Auth::login($user);

            $domain = $tenant->domains()->first();

            if (! $domain) {
                // Fallback if no domain exists yet
                return redirect('/some-central-success-page')->with('success', 'Company created!');
            }

            $base = rtrim(config('app.url'), '/');  // e.g. http://localhost:8000 or http://stockify.com

            // For subdomain identification (your current setup): domain column = 'test5' (not full hostname)
            $tenantUrl = str_replace('://', "://{$domain->domain}.", $base);  // → http://test5.localhost:8000

            // If you ever store full hostname in domains table (custom domains), use:
            // $tenantUrl = $baseScheme . '://' . $domain->domain;

            $adminUrl = $tenantUrl.'/admin';   // or '/dashboard', or route('tenant.admin')

            DB::commit();

            return redirect()->to($adminUrl)
                ->with('success', 'Company account created successfully!');

            // Redirect to tenant domain based or subdomain  dashboard
            return redirect($url)->with('success', 'Company account created successfully!');

        } catch (\Exception $e) {
            Log::error('Tenant registration failed', [
                'subdomain' => $this->subdomain,
                'email' => $this->email,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            DB::rollBack();
            $errorMessage = config('app.debug')
                ? 'Registration failed: '.$e->getMessage()
                : 'Registration failed. Try a different subdomain or contact support.';

            $this->addError('subdomain', $errorMessage);

            // Cleanup: delete tenant and user if they were created (using raw DB to avoid job dispatch)
            try {
                if (isset($user)) {
                    DB::table('users')->where('id', $user->id)->delete();
                }
                if (isset($tenantId)) {
                    DB::table('tenants')->where('id', $tenantId)->delete();
                }
            } catch (\Exception $cleanupError) {
                Log::error('Failed to cleanup after registration error', [
                    'tenant_id' => $tenantId ?? null,
                    'user_id' => $user->id ?? null,
                    'error' => $cleanupError->getMessage(),
                ]);
            }

            report($e);
        }
    }

    public function render()
    {
        return view('livewire.tenant.register');
    }
}
