<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
//use Illuminate\Support\Facades\Schema;
use App\models\Setting;

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
        $filename = storage_path("installed");
        if (file_exists($filename)) {
            config([
                'global' => Setting::all([
                            'group','name','value'
                        ])
                        ->groupBy('group')
                                    
                        ->transform(function ($settings) {
                            $settings_ary = array();
                            foreach ($settings as $setting){
                                $settings_ary[$setting['name']] = $setting['value'];
                            }
                            return $settings_ary; // return only the value
                        })
                        ->toArray() // make it an array
            ]);
        }
    }
}
