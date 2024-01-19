<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\Event;
use Illuminate\Auth\Access\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
//        Gate::define('update-event', function ($user, Event $event) {
//            return $user->id === $event->user_id;
//        });
//
//        Gate::define('delete-event', function ($user, Event $event) {
//            return $user->id === $event->user_id || $user->id === $event->attendees->pluck('user_id')->toArray();
//        });
    }
}
