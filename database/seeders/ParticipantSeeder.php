<?php

namespace Database\Seeders;

use App\Models\Participant;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ParticipantSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(?string $path = null): void
    {
        if (null === $path) {
            $matches = [database_path('seeders' . DIRECTORY_SEPARATOR . 'participants.json')];
            if (!file_exists($matches[0])) {
                return;
            }
        } else {
            $matches = glob($path);
            if (empty($matches)) {
                $this->command?->comment('File not found: ' . $path);
                return;
            }
        }
        foreach ($matches as $path) {
            $items = json_decode(\file_get_contents($path), true);
            $skipped = 0;
            $created = 0;
            foreach ($items as $item) {
                if (Participant::where('name', $item['name'])->exists()) {
                    $skipped++;
                } else {
                    Participant::create($item);
                    $created++;
                }
            }
            if ($skipped) {
                $this->command?->warn($created . ' Participants created and ' . $skipped . ' Participants skipped.');
            } else {
                $this->command?->info($created . ' Participants created.');
            }
        }
    }
}
