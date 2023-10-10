<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'frequency',
        'repeat_until',
        'event_id',
    ];

    protected function start(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('c'),
            set: fn (string $value) => Carbon::parse($value),
        );
    }

    protected function end(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('c'),
            set: fn (string $value) => Carbon::parse($value),
        );
    }

    protected function repeatUntil(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('c'),
            set: fn (string $value) => Carbon::parse($value),
        );
    }
}
