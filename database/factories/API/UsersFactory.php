<?php

namespace Database\Factories\API;

use Illuminate\Database\Eloquent\Factories\Factory;

class UsersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
                "name" => $this->faker->name(),
                "last_name" => $this->faker->lastname(),
                "email" =>$this->faker->unique()->safeEmail(),
                "phone" => $this->faker->unique()->numerify('############'),
                "password" => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                "active" => $this->faker->numerify(),
                "role_id" =>$this->faker->numerify(),
                "permission_id" =>$this->faker->numerify()
        ];
    }
}
