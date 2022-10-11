<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            $spaUrl = "http://localhost:3000/verify-email-url?urlVerify=" . $url;
            return (new MailMessage)
                ->greeting('Hello! ' . $notifiable->name)
                ->subject('Hikky_bookstore verify email')
                ->line('Welcome to Hikky bookstore')
                ->line('Click the button below to verify your email address.')
                ->action('Verify Email Address',  $spaUrl);
        });
    }
}
