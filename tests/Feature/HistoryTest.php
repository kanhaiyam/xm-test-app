<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Symbol;

class HistoryTest extends TestCase
{
    use DatabaseMigrations;

    public function test_index(): void
    {
        $response = $this->call('GET', 'history');
        $response->assertOk();
    }

    public function test_get_historial_data_with_no_data(): void
    {
        $response = $this->call('POST', 'history');
        $response->assertStatus(302);
        $response->assertInvalid(['symbol', 'email', 's_date', 'e_date']);
    }

    public function test_get_historial_data_with_valid_data(): void
    {
        $symbol = Symbol::factory()->create();

        //Tests if the symbol exists
        $this->assertDatabaseHas('symbols', [
            'symbol' => 'APPL',
        ]);

        $inputs = [
            'symbol' => 'APPL',
            's_date' => date('m/d/Y', strtoTime('-30 days')),
            'e_date' => date('m/d/Y', strtoTime('-15 days')),
            'email' => 'example@test.com'
        ];

        $response = $this->call('POST', 'history', $inputs);
        $response->assertStatus(200);
        $response->assertSessionHasNoErrors();
        $response->assertValid(['symbol', 'email', 's_date', 'e_date']);
    }


    public function test_get_historial_data_with_in_valid_dates(): void
    {
        $symbol = Symbol::factory()->create();

        $inputs = [
            'symbol' => 'APPL',
            's_date' => date('m/d/Y', strtoTime('+30 days')),
            'e_date' => date('m/d/Y', strtoTime('-15 days')),
            'email' => 'example@test.com'
        ];

        $response = $this->call('POST', 'history', $inputs);
        $response->assertStatus(302);
        $response->assertValid(['symbol', 'email',]);
        $response->assertInvalid(['s_date', 'e_date']);
        $response->assertSessionHasErrors(['s_date', 'e_date']);
    }

    public function test_get_historial_data_with_in_valid_end_date(): void
    {
        $symbol = Symbol::factory()->create();

        $inputs = [
            'symbol' => 'APPL',
            's_date' => date('m/d/Y', strtoTime('-7 days')),
            'e_date' => date('m/d/Y', strtoTime('+1 days')),
            'email' => 'example@test.com'
        ];

        $response = $this->call('POST', 'history', $inputs);
        $response->assertStatus(302);
        $response->assertValid(['symbol', 'email','s_date']);
        $response->assertInvalid(['e_date']);
        $response->assertSessionHasErrors(['e_date']);
    }

    public function test_view_key_and_headline_data(): void
    {    
        $symbol = Symbol::factory()->create();

        //Tests if the symbol exists
        $this->assertDatabaseHas('symbols', [
            'symbol' => 'APPL',
        ]);

        $inputs = [
            'symbol' => 'APPL',
            's_date' => date('m/d/Y', strtoTime('-30 days')),
            'e_date' => date('m/d/Y', strtoTime('-15 days')),
            'email' => 'example@test.com'
        ];

        $response = $this->call('POST', 'history', $inputs);
        $response->assertStatus(200);
        $response->assertValid(['symbol', 'email', 's_date', 'e_date']);
        $response->assertViewHas('firstLine',"Apple Inc. - Common Share - 03/24/2023 to 04/08/2023");
        $response->assertViewHas('tableData',null); //table data is null because the API was not hit
    }
}
