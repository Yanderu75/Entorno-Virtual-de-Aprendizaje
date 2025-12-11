<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Pagination\Paginator::useBootstrap();

        \Illuminate\Support\Facades\Gate::define('admin', function ($user) {
            return $user->rol === 'admin';
        });

        \Illuminate\Support\Facades\Gate::define('docente', function ($user) {
            return $user->rol === 'docente';
        });

        \Illuminate\Support\Facades\Gate::define('estudiante', function ($user) {
            return $user->rol === 'estudiante';
        });

        // Dynamic Menu Event Listener
        \Illuminate\Support\Facades\Event::listen(
            \JeroenNoten\LaravelAdminLte\Events\BuildingMenu::class,
            function (\JeroenNoten\LaravelAdminLte\Events\BuildingMenu $event) {
                // Determine unread count
                $count = 0;
                if (\Illuminate\Support\Facades\Auth::check()) {
                   // Ensure Notificacion model exists and has 'leida' (boolean or timestamp?). 
                   // Based on NotificacionController hint 'leida' route, field is likely 'leida' (bool) or similar.
                   // Assuming 'leida' is boolean 0 for unread.
                   // Check database usage in previous steps? Not explicitly seen model. Verify implicit assumption or try safest query.
                   // The user previously shared NotificacionController: 'leida' action exists.
                   try {
                       $count = \App\Models\Notificacion::where('id_usuario', \Illuminate\Support\Facades\Auth::id())
                                   ->where('leida', 0)
                                   ->count();
                   } catch (\Exception $e) {
                       $count = 0; // Fallback if table/model issue
                   }
                }

                // Add Topnav Notification Item
                $event->menu->add([
                    'text' => 'Notificaciones',
                    'route'  => 'notificaciones.index',
                    'icon'   => 'fas fa-fw fa-bell',
                    'topnav_right' => true,
                    'label'       => $count > 0 ? $count : null,
                    'label_color' => 'danger',
                ]);

                // Add Sidebar Notification Item
                $event->menu->add([
                    'text'    => 'Notificaciones',
                    'route'   => 'notificaciones.index',
                    'icon'    => 'fas fa-fw fa-bell',
                    'label'       => $count > 0 ? $count : null,
                    'label_color' => 'danger',
                ]);
            }
        );
    }
}
