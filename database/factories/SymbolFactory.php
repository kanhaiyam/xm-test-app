<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Symbol;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Symbol>
 */
class SymbolFactory extends Factory
{

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Symbol::class;


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id' => 'ee6b7271-f563-4769-a267-daa81c980b5a',
            'c_name' => 'Apple Inc.',
            's_name' => 'Apple Inc. - Common Share',
            'symbol' => 'APPL',
            'f_status' => 'N',
            'm_category' => 'Q',
            'lot_size' => 100.0,
            'is_test' => 'N',
            'created_at' => '2023-04-21 14:26:47',
            'updated_at' => '2023-04-21 14:26:47'
        ];
    }
}
