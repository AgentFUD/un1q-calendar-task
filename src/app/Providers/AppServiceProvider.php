<?php

namespace App\Providers;

use App\Models\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

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
        Event::observe(RecurrenceOserver::class);
        
        Validator::extend('event_overlap', function ($attribute, $value, $parameters, $validator) {
            if ($attribute == 'start' || $attribute == 'end') {
                $overlap_count = DB::table('events')
                    ->where([
                        ['start', '<=', Carbon::parse($value)],
                        ['end', '>=', Carbon::parse($value)],
                    ])
                    ->count();

                return $overlap_count == 0 ?? false;
            }
        });
    }
}
