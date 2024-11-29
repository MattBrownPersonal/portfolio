<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Deceased' => 'App\Policies\DeceasedPolicy',
        'App\Models\Article' => 'App\Policies\ArticlesPolicy',
        'App\Models\Faq' => 'App\Policies\FaqsPolicy',
        'App\Models\Memorialisations' => 'App\Policies\MemorialisationsPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
