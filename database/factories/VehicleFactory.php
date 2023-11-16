<?php

namespace Database\Factories;

use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vehicle>
 */
class VehicleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'make' => fake()->word,
            'model' => fake()->word,
            'year' => fake()->year,
            'photo' => fake()->imageUrl(640, 480, 'vehicle', true),
            'plate_number' => fake()->randomElement(
                [static::oddPlate(), static::evenPlate()]
            ),
            'status' => fake()->randomElement(
                ['operational', 'maintenance', 'decomissioned']
            )
        ];
    }

    private static function front(): string
    {
        return fake()->regexify('[A-Z]{1,2}');
    }

    private static function back(): string
    {
        return fake()->regexify('[A-Z]{1,3}');
    }

    private static function oddPlate(): string
    {
        $odd = fake()->randomNumber(4, false) | 1;
        return sprintf('%s%d%s', static::front(), $odd, static::back());
    }

    private static function evenPlate(): string
    {
        $even = fake()->randomNumber(4, false) & ~1;
        return sprintf('%s%d%s', static::front(), $even, static::back());
    }

    public function odd(): Factory
    {
        return $this->state(fn () => ['plate_number' => static::oddPlate()]);
    }

    public function even(): Factory
    {
        return $this->state(fn () => ['plate_number' => static::evenPlate()]);
    }
}
