<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    // protected $router;
    // public function __construct()
    // {
    //      $router = RouteService();
    // }
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    protected $apiNamespace = 'App\Http\Controllers\api\v2';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Define the routes for the application.
     */
    public function map(): void
    {
        // USSD first: POST / must win over any later broad "/" handlers (Laravel returns first match).
        $this->mapUssdRoutes();
        $this->mapApiRoutes();
        $this->mapV2ApiRoutes();
        $this->mapWebRoutes();
        $this->mapAdminRoutes();
        $this->mapStudentRoutes();
        $this->mapParentRoutes();
        $this->mapTeacherRoutes();
        $this->mapConfigureRoutes();
        $this->mapPageBuilderRoutes();
        $this->mapGraduateRoutes();
    }

    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    protected function mapAdminRoutes()
    {
        Route::middleware(['web', '2fa', 'auth'])
            ->namespace($this->namespace)
            ->group(base_path('routes/admin.php'));
    }

    protected function mapStudentRoutes()
    {
        Route::middleware(['web', 'auth', '2fa', 'fees_due_check'])
            ->namespace($this->namespace)
            ->group(base_path('routes/student.php'));
    }

    protected function mapParentRoutes()
    {
        Route::middleware(['web', 'auth', '2fa', 'fees_due_check'])
            ->namespace($this->namespace)
            ->group(base_path('routes/parent.php'));
    }

    protected function mapTeacherRoutes()
    {
        Route::middleware(['web', 'auth', '2fa'])
            ->namespace($this->namespace)
            ->group(base_path('routes/teacher.php'));
    }

    protected function mapGraduateRoutes()
    {
        Route::middleware(['web', 'auth', '2fa'])
            ->namespace($this->namespace)
            ->group(base_path('routes/graduate.php'));
    }

    protected function mapPageBuilderRoutes()
    {
        Route::middleware(['web','subdomain'])->group(base_path('routes/pagebuilder.php'));
    }

    /**
     * USSD webhooks (Africa's Talking) + M-Pesa STK callback.
     *
     * Loaded before heavy web routes so POST / can proxy root USSD callbacks.
     * No web session / CSRF — see routes/ussd.php and routes/ussd_public.php.
     * Callback URL for AT is not stored here; set APP_URL (or USSD_* in .env) and run
     * `php artisan ussd:callbacks` on the server to print the full URL for the dashboard.
     *
     * @return void
     */
    protected function mapUssdRoutes(): void
    {
        $ussdMiddleware = [
            \App\Http\Middleware\OptimizeUssdRequest::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \App\Http\Middleware\LogUssdWebhook::class,
        ];

        Route::prefix('api')
            ->middleware($ussdMiddleware)
            ->namespace($this->namespace)
            ->group(base_path('routes/ussd.php'));

        // Shorter URL (same handler) — use if Africa's Talking dashboard truncates or omits /api by mistake.
        Route::middleware($ussdMiddleware)
            ->namespace($this->namespace)
            ->group(base_path('routes/ussd_public.php'));
    }

    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(base_path('routes/api.php'));
    }

    protected function mapV2ApiRoutes()
    {
        Route::prefix('api/v2')
            ->middleware('api')
            ->namespace($this->apiNamespace)
            ->group(base_path('routes/v2api.php'));
    }

    // configuration route

    protected function mapConfigureRoutes()
    {
        Route::namespace($this->namespace)
            ->group(base_path('routes/configuration.php'));
    }
}
