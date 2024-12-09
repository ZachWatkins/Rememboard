<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Event;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(100),
            'description' => $this->faker->text(255),
            'start_date' => $this->faker->dateTime(),
            'end_date' => $this->faker->dateTime(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'city' => $this->faker->city(),
            'state' => $this->faker->text(100),
            'folder_name' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'show_on_countdown' => $this->faker->boolean(),
            'is_trip' => $this->faker->boolean(),
        ];
    }

    /**
     * Indicate that the event has a timezone of America/New_York.
     */
    public function eastern(): EventFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'timezone' => 'America/New_York',
                'timezone_offset' => '-04:00',
            ];
        });
    }

    /**
     * Indicate that the event has a timezone of America/Los_Angeles.
     */
    public function pacific(): EventFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'timezone' => 'America/Los_Angeles',
                'timezone_offset' => '-07:00',
            ];
        });
    }

    /**
     * Indicate that the event has a timezone of America/Chicago.
     */
    public function central(): EventFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'timezone' => 'America/Chicago',
                'timezone_offset' => '-05:00',
            ];
        });
    }

    /**
     * Indicate that the event has a timezone of America/Denver.
     */
    public function mountain(): EventFactory
    {
        return $this->state(function (array $attributes) {
            return [
                'timezone' => 'America/Denver',
                'timezone_offset' => '-06:00',
            ];
        });
    }
}
