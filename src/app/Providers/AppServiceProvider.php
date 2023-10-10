<?php

namespace App\Providers;

use App\Domains\Event\Observers\RecurrenceOserver;
use App\Models\Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
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
        Event::observe(RecurrenceOserver::class);

        Validator::extend('event_overlap', function ($attribute, $value, $parameters, $validator) {
            if ($attribute == 'start' || $attribute == 'end') {
                if (count($parameters) == 0) {
                    $overlap_count = DB::table('events')
                        ->where([
                            ['start', '<=', Carbon::parse($value)],
                            ['end', '>=', Carbon::parse($value)],
                        ])
                        ->count();
                } else {
                    $overlap_count = DB::table('events')
                        ->where([
                            ['start', '<=', Carbon::parse($value)],
                            ['end', '>=', Carbon::parse($value)],
                            ['id', '<>', (int) $parameters[0]],
                        ])
                        ->count();
                }

                return $overlap_count == 0 ?? false;
            }
        });
    }
}
