<?php

namespace Database\Factories;

use App\Models\Sender;
use App\Models\Status;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Mail>
 */
class MailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'subject' => fake()->title(),
            'description' => fake()->paragraph(2),
            'sender_id' => Sender::factory(),
            'archive_number' => fake()->randomNumber(),
            'archive_date' => fake()->date(),
            'decision' => fake()->paragraph(1),
            'status_id' => Status::all()->random(),
        ];
    }
}
