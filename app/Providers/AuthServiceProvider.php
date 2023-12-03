<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Contact;
use App\Models\Medium;
use App\Models\User;
use App\Policies\ContactPolicy;
use App\Policies\MediumPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Contact::class => ContactPolicy::class,
        Medium::class => MediumPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::viaRequest('ulid-token', function (Request $request) {
            return User::where('ulid_token', (string) $request->bearerToken())->first();
        });
    }
}
