<?php

namespace App\Services;

use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GeolocationService
{
    public function getCoordinates(string $address): array
    {
        $existing = Event::where('address', $address)->whereNotNull('latitude')->whereNotNull('longitude')->first();
        if ($existing) {
            return $existing->only('latitude', 'longitude');
        }

        $key = preg_replace('/[^a-zA-Z0-9]/', '_', $address);
        return Cache::remember("geolocation_{$key}", 60 * 60 * 24, function () use ($address) {
            $usage = DB::table('usage')->where('month', now()->format('F'))->get();
            if ($usage > intval(config('services.azure.maps.monthly_limit'))) {
                throw new \Exception('Daily limit exceeded');
            }
            DB::table('usage')->where('month', now()->format('F'))->increment('amount');

            $response = Http::withHeaders([
                'Accept-Language' => 'en-US',
                'x-ms-client-id' => config('services.azure.maps.client_id'),
            ])->get('https://atlas.microsoft.com/geocode', [
                'api-version' => '2023-06-01',
                'query' => $address,
                'top' => 1,
                'subscription-key' => config('services.azure.maps.subscription_key'),
            ]);

            if ($response->successful()) {

                $data = $response->json();
                if (!empty($data['results'])) {
                    $position = $data['results'][0]['position'];
                    return [
                        'latitude' => $position['lat'],
                        'longitude' => $position['lon'],
                    ];
                }
            }

            throw new \Exception('Unable to fetch geolocation data');
        });
    }
}
