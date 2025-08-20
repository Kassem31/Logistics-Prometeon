<?php

namespace App\Providers;

use App\Models\Menu;
use App\Observers\ModelObserver;
use App\Contracts\Loggable;
use App\Models\Inbound;
use App\Models\InboundDetails;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Models\ShippingBasicInfo;
use App\Models\ShippingBooking;
use App\Models\ShippingClearance;
use App\models\ShippingDelivery;
use App\Models\ShippingDocument;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('permission', function ($expression) {
            return "<?php if (Auth::user()->is_super_admin || app('laratrust')->hasPermission({$expression})) : ?>";

            if(Auth::user()->is_super_admin){
                return "<?php if (1) : ?>";
            }else{
                return "<?php if (app('laratrust')->hasPermission({$expression})) : ?>";

            }
        });
        Schema::defaultStringLength(191);

        View::composer('partials._header._menu',function($view){
            $menus = Menu::generate();
            $view->with('menuGroups',$menus);
        });
        $this->observeLoggableClasses();
        Inbound::observe(ModelObserver::class);
        InboundDetails::observe(ModelObserver::class);
        ShippingBasicInfo::observe(ModelObserver::class);
        ShippingBooking::observe(ModelObserver::class);
        ShippingDocument::observe(ModelObserver::class);
        ShippingClearance::observe(ModelObserver::class);
        ShippingDelivery::observe(ModelObserver::class);


    }

    protected function loadLoggableClasses(){
        $classes = [];
        foreach (glob('app/Models/*.php') as $file)
        {
            $class = basename($file, '.php');
            $class = 'App\\Models\\'.$class;
            $classes[] = $class;
        }
        $classes = collect($classes)->filter(function($item){
            return (new \ReflectionClass($item))->implementsInterface(Loggable::class);

        });
        return $classes;
    }

    protected function observeLoggableClasses(){
        $this->loadLoggableClasses()->each(function($item){
            $item::observe(ModelObserver::class);
        });
    }
}
