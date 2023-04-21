<?php

namespace Database\Seeders;

use App\Models\Symbol;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class SymbolSeeder extends Seeder
{
    /**
     * Refresh the symbols table.
     */
    public function run(): void
    {
        DB::table('symbols')->truncate(); //Empty the previously fed symbols data.

        $symbolsData = file_get_contents('https://pkgstore.datahub.io/core/nasdaq-listings/nasdaq-listed_json/data/a5bc7580d6176d60ac0b2142ca8d7df6/nasdaq-listed_json.json');
        $symbolsData = array_chunk(json_decode($symbolsData, true), 100); //Chunking the array to only insert 100 Symbols at a time.
        $symbolsForDB = [];
        foreach ($symbolsData as $chunk => $symbolChunk) {
            foreach ($symbolChunk as $key => $symbol) {
                $symbol = $this->mapData($symbol);
                isset($symbol) ? array_push($symbolsForDB, $symbol) : '';
            }

            //Insert the chunk into DB
            if (! empty($symbolsForDB)) {
                Symbol::insert($symbolsForDB);
            }

            $symbolsForDB = [];
        }
    }

    /**
     * Map the columns with that of the database table
     */
    protected function mapData(array $symbol): mixed
    {
        if (7 !== count($symbol)) {
            return null;
        }

        //Make No Defaault for ENUM Column is_test

        if ('Y' !== $symbol['Test Issue']) {
            $symbol['Test Issue'] = 'N';
        }

        return [
            'c_name' => $symbol['Company Name'],
            's_name' => $symbol['Security Name'],
            'symbol' => $symbol['Symbol'],
            'f_status' => $symbol['Financial Status'],
            'm_category' => $symbol['Market Category'],
            'lot_size' => floatval($symbol['Round Lot Size']),
            'is_test' => $symbol['Test Issue'],
            'updated_at' => Carbon::now(),
            'id' => Uuid::uuid4()->toString(),
        ];
    }
}
