<?php

namespace Database\Seeders;

use App\Models\Channel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use PhpParser\ErrorHandler\Collecting;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $file = file_get_contents(base_path("stock_market_data.json"));
        $file = json_decode($file);
        $file  = Collection::wrap($file);

        $file->each(function($e,$i){
            Channel::create([
                "trade_code" => $e->trade_code,
                "date" => $e->date,
                "high" => str_replace(',', '', $e->high),
                "low" => str_replace(',', '', $e->low),
                "open" => str_replace(',', '', $e->open),
                "close" => str_replace(',', '', $e->close),
                "volume" => str_replace(',', '', $e->volume),
            ]);
        });
    }
}
