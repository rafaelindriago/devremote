<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FetchLanguages extends Command
{
    protected $signature = 'app:fetch-languages';

    protected $description = 'Fetch the languages data from GitHub';

    public function handle(): void
    {
        $this->info('Starting to fetch and save languages data');

        DB::beginTransaction();

        $languagesMediaJson = Http::retry(3, 1000)
            ->get('https://raw.githubusercontent.com/devicons/devicon/master/devicon.json')
            ->body();

        $languageMediaArray = json_decode($languagesMediaJson);
        $languagesMediaCount = count($languageMediaArray);

        $languagesJson = Http::retry(3, 1000)
            ->get('https://api.github.com/languages')
            ->body();

        $languagesArray = json_decode($languagesJson);

        $this->withProgressBar($languageMediaArray, function (object $languageMedia) use ($languagesArray): void {
            $languageMedia->class = "devicon-{$languageMedia->name}-plain colored";

            foreach ($languagesArray as $languageObject) {
                if ($languageMedia->name === mb_convert_case($languageObject->name, MB_CASE_LOWER)) {
                    Language::query()
                        ->upsert([
                            'name' => $languageObject->name,
                            'data' => json_encode($languageMedia),
                        ], 'name');

                    return;
                }
            }

            Language::query()
                ->upsert([
                    'name' => mb_convert_case($languageMedia->name, MB_CASE_TITLE),
                    'data' => json_encode($languageMedia),
                ], 'name');
        });

        DB::commit();

        $this->newLine();

        $this->info("{$languagesMediaCount} languages fetched and saved to database!");
    }
}
