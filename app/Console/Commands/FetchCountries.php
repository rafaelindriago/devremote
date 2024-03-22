<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FetchCountries extends Command
{
    protected $signature = 'app:fetch-countries';

    protected $description = 'Fetch the countries data from "https://restcountries.com" API';

    public function handle(): void
    {
        $this->info('Starting to fetch and save countries data');

        DB::beginTransaction();

        $countriesJson = Http::retry(1, 1000)
            ->get('https://restcountries.com/v3.1/all', [
                'fields' => 'name,cca3',
            ])
            ->body();

        $countriesArray = json_decode($countriesJson);
        $countriesCount = count($countriesArray);

        $this->withProgressBar($countriesArray, function (object $countryObject): void {
            $dataJson = Http::retry(3, 1000)
                ->get("https://restcountries.com/v3.1/alpha/{$countryObject->cca3}", [
                    'fields' => 'flags,idd,timezones,currencies',
                ])
                ->body();

            DB::table('countries')
                ->upsert([
                    'code' => $countryObject->cca3,
                    'name' => $countryObject->name->common,
                    'data' => $dataJson,
                ], 'code', [
                    'data' => $dataJson,
                ]);
        });

        DB::commit();

        $this->newLine();

        $this->info("{$countriesCount} countries fetched and saved to database!");
    }
}
