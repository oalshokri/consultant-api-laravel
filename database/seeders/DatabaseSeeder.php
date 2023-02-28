<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Category;
use App\Models\Mail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Role;
use App\Models\Status;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::insert(
            ['name' => 'guest', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
        Role::insert(
            ['name' => 'user', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
        Role::insert(
            ['name' => 'editor', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
        Role::insert(
            ['name' => 'admin', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        );

        Status::insert(
            ['name' => 'Inbox', 'color' => '0xfffa3a57', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
        Status::insert(
            ['name' => 'Pending', 'color' => '0xffffe120', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
        Status::insert(
            ['name' => 'In Progress', 'color' => '0xff6589ff', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        );
        Status::insert(
            ['name' => 'Completed', 'color' => '0xff77d16f', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        );

        Category::insert(
            ['name' => 'Other', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        );
        Category::insert(
            ['name' => 'Official Organizations', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
        Category::insert(
            ['name' => 'NGOs', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        );
        Category::insert(
            ['name' => 'Foreign', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        );


        \App\Models\User::factory(5)->create();

        \App\Models\Mail::factory(5000)->hasActivities(3, function (array $attributes, Mail $mail) {
            return ['mail_id' => $mail->id];
        })->create();
    }
}
