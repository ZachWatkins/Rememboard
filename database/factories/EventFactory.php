<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        $startDate = $this->faker->dateTimeBetween('-5 years', '-1 year')->format('Y-m-d H:i:s');
        return [
            'name' => $this->faker->name(100),
            'description' => $this->faker->text(255),
            'start_date' => $startDate,
            'end_date' => $this->faker->optional()->dateTimeBetween($startDate)?->format('Y-m-d H:i:s'),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'address' => $this->faker->address(),
            'city' => $this->faker->city(),
            'state' => $this->faker->text(100),
            'folder_name' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'show_on_countdown' => $this->faker->boolean(),
            'is_trip' => $this->faker->boolean(),
        ];
    }

    public function withEndDate(): static
    {
        return $this->state(fn(array $attributes) => [
            'end_date' => $this->faker->dateTimeBetween($attributes['start_date'])->format('Y-m-d H:i:s'),
        ]);
    }
}
