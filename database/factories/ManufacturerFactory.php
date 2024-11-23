<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Manufacturer;

class ManufacturerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Manufacturer::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'website' => $this->faker->regexify('[A-Za-z0-9]{100}'),
            'phone' => $this->faker->phoneNumber(),
            'filename' => $this->faker->regexify('[A-Za-z0-9]{100}'),
        ];
    }
}