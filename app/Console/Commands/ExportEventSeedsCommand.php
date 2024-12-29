<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ExportEventSeedsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:export-events-seeds';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export Events in the database to the default seed file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $path = database_path('seeders/events.json');
        $events = Event::all()->load('participants')->sortBy('start_date')->map(function ($event) {
            $item = $event->toArray();
            $item['participants'] = $event->participants->pluck('name');
            unset($item['id']);
            unset($item['countdown']);
            return $item;
        })->toArray();
        $contents = json_encode(array_values($events), JSON_PRETTY_PRINT);
        File::replace($path, $contents);
    }
}
