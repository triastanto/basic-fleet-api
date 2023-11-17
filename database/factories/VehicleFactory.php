<?php

namespace Database\Factories;

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
        $definition = [
            'name' => fake()->name,
            'make' => fake()->word,
            'model' => fake()->word,
            'year' => fake()->year,
            'photo' => fake()->imageUrl(640, 480, 'vehicle', true),
            'status' => fake()->randomElement(
                ['operational', 'maintenance', 'decomissioned']
            )
        ];

        $plate_number = fake()->randomElement([
            static::oddState(), static::evenState()
        ]);

        return array_merge($definition, $plate_number);
    }

    /**
     * Indicate that the vehicle has odd plate number
     */
    public function odd(): Factory
    {
        return $this->state(fn () => static::oddState());
    }

    /**
     * Indicate that the vehicle has even plate number
     */
    public function even(): Factory
    {
        return $this->state(fn () => static::evenState());
    }

    /**
     * Return columns definition for odd plate number
     *
     * @return array array of 'plate_number' and 'meta' column definition
     */
    private static function oddState(): array
    {
        return ['plate_number' => static::fakeOdd(), 'meta' => ['odd' => true]];
    }

    /**
     * Return columns definition for even plate number
     *
     * @return array array of 'plate_number' and 'meta' column definition
     */
    private static function evenState(): array
    {
        return ['plate_number' => static::fakeEven(), 'meta' => ['even' => true]];
    }

    /**
     * Return the fake front part of the plate number
     */
    private static function front(): string
    {
        return fake()->regexify('[A-Z]{1,2}');
    }

    /**
     * Return the fake back part of the plate number
     */
    private static function back(): string
    {
        return fake()->regexify('[A-Z]{0,3}');
    }

    /**
     * Combine the front part, random even number, and back part.
     *
     * @return string fake even plate number
     */
    private static function fakeOdd(): string
    {
        $odd = fake()->randomNumber(4, false) | 1;
        return sprintf('%s%d%s', static::front(), $odd, static::back());
    }

    /**
     * Combine the front part, random odd number, and back part.
     *
     * @return string fake odd plate number
     */
    private static function fakeEven(): string
    {
        $even = fake()->randomNumber(4, false) & ~1;
        return sprintf('%s%d%s', static::front(), $even, static::back());
    }
}
