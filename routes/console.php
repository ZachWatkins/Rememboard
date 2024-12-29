<?php

use App\Models\Event;
use App\Models\Participant;
use App\Services\GeolocationService;
use Illuminate\Foundation\Inspiring;
use App\Services\File\IcsFileAdapter;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
