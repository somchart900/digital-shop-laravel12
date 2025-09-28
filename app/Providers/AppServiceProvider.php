<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }




    public function boot(): void
    {
        Carbon::setLocale('th');

        Gate::define('admin', function (User $user) {
            return $user->level == '99';
        });

        Gate::define('enablebackend', function (User $user) {
            if ($user->level == '99') return true;
            if (Auth::check()) {
 
                $check = Cache::rememberForever('settings', function () {
                    return Setting::where('name', 'enablebackend')->first();
                });
                if (!empty($check) && $check->value) {
                    return true;
                }
            }
            return false;
        });

        // แชร์ให้ทุก view และแคช แบบไม่มีหมดอายุ ล้างแคชเมื่อมีการเปลี่ยนแปลงล้างใน controller
        $settings = Cache::rememberForever('settings_all', function () {
            return Setting::whereIn('name', [
                'webname',
                'facebook',
                'discord',
                'youtube',
                'messenger',
                'line',
                'announce',
                'announce2'
            ])->get()->keyBy('name');
        });
        view()->share([
            'webname' => $settings['webname'] ?? null,
            'facebook' => $settings['facebook'] ?? null,
            'discord' => $settings['discord'] ?? null,
            'youtube' => $settings['youtube'] ?? null,
            'messenger' => $settings['messenger'] ?? null,
            'line' => $settings['line'] ?? null,
            'popup' => $settings['announce'] ?? null,
            'popup2' => $settings['announce2'] ?? null,
        ]);
    }
}
