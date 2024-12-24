<?php

namespace App\Providers;

use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot()
    {
        parent::boot();
    
        Event::listen(Authenticated::class, function ($event) {
            $user = $event->user;
    
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
    
            if ($user->isClient()) {
                return redirect()->route('client.dashboard');
            }
    
            if ($user->isTechnician()) {
                return redirect()->route('technician.dashboard');
            }
    
            return redirect()->route('index'); // Fallback route
        });
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
