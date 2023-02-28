<?php

namespace Database\Factories;

use App\Models\Mail;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'body' => fake()->paragraph(2),
            'user_id' => User::all()->random(),
            'mail_id' => Mail::factory(),
        ];
    }
}
