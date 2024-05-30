<?php

namespace Database\Factories;

use App\Models\tmp\Reservation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(1, 2),
            'event_id' => $this->faker->numberBetween(1, 4),
            'females' => $this->faker->numberBetween(0, 3),
            'males' => $this->faker->numberBetween(0, 3),
            'notes' => $this->faker->sentence(),
            'canceled_at' => null,
            'cancel_reason' => null,
            'canceled_by' => null,
            'confirmed_at' => null,
            'confirmed_by' => null,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }
}
