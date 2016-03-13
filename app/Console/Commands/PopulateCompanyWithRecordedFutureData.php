<?php

namespace App\Console\Commands;

use App\Entities\Company;
use App\Entities\Country;
use App\Entities\Region;
use App\Entities\Vector;
use App\Services\Vendors\RecordedFuture;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateCompanyWithRecordedFutureData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company-populate-year {company}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates a given company with Recorded Future entities for a year.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        /** @var Company $company */
        $company = Company::whereName($this->argument('company'))->first();
        if (!$company) {
            exit;
        }

        foreach (Company::where('id', '<>', 1)->get() as $company) {
            $this->saveCompanyData($company);
        }
    }

    protected function saveCompanyData($company)
    {
        $time_start = microtime(true);
        $rf = app(RecordedFuture::class);
        $rf2 = app(RecordedFuture::class);
        $rf->setLimit(2000);
        $rf2->setLimit(0);

        $done = false;
        $safetyValve = 0;
        $startPage = 0;
        while (!$done && $safetyValve <= 10000000) {
            $results = $rf->setPageStart($startPage)
                ->instancesForEntity($company->entity_id, 365);

            if (!$nextPageStart = array_get($results, 'next_page_start')) {
                $done = true;
            } else {
                $startPage = $nextPageStart;
            }

            $timestamp = date('Y-m-d H:i:s');
            foreach (array_get($results, 'instances') as $instance) {
                if (DB::table('instances')->where('fragment', array_get($instance, 'fragment'))->first()) {
                    continue;
                }

                $positiveSentiment = array_get($instance, 'attributes.general_positive');
                $negativeSentiment = array_get($instance, 'attributes.general_negative');

                if ($negativeSentiment == $positiveSentiment) {
                    continue;
                }

                try {
                    $eventType = array_get($instance, 'type');
                    $countryId = null;
                    $countryName = trim(array_get($instance, 'document.sourceId.country', ''));
                    if (!$countryName && $relatedEntityCodes = array_get($instance, 'attributes.entities')) {
                        foreach(array_get($rf2->getEntitiesByCodes($relatedEntityCodes), 'entity_details') as $entityId => $entity) {
                            if (array_get($entity, 'name') && array_get($entity, 'type') == 'Country') {
                                $countryName = array_get($entity, 'name');
                                break;
                            }
                        }
                    }

                    if ($countryName) {
                        $country = \DB::table('countries')->where('name', $countryName)->first(['id']);
                        if (!$country) {
                            $countryId = DB::table('countries')->insertGetId(['name' => $countryName]);
                        } else {
                            $countryId = $country->id;
                        }
                    }

                    $regionId = null;
                    if ($region = $rf2->continentFromCountry($countryName)) {
                        $regionEntityId = key($region);
                        $region = current($region);

                        if (!$region = \DB::table('regions')->where('name', $region['name'])->first(['id'])) {
                            $regionId = DB::table('regions')->insertGetId(['name' => $region['name'], 'entity_id' => $regionEntityId]);
                        } else {
                            $regionId = $region->id;
                        }
                    }

                    $publishedAt = null;
                    if ($documentPublishedAt = array_get($instance, 'document.published')) {
                        $publishedAt = (new Carbon($documentPublishedAt))->toDateTimeString();
                    }

                    $vectorId = null;
                    if ($vector = Vector::fromEventType($eventType)) {
                        $vectorId = $vector->id;
                    };

                    $fragment = trim(array_get($instance, 'fragment'));
                    DB::table('instances')->insert([
                        'company_id' => $company->id,
                        'vector_id' => $vectorId,
                        'country_id' => $countryId,
                        'region_id' => $regionId,
                        'entity_id' => array_get($instance, 'id'),
                        'event_type' => $eventType,
                        'original_language' => trim(array_get($instance, 'document.language')),
                        'source' => trim(array_get($instance, 'document.sourceId.name')),
                        'title' => trim(array_get($instance, 'document.title')),
                        'fragment' => $fragment,
                        'fragment_hash' => md5($fragment),
                        'link' => trim(array_get($instance, 'document.url')),
                        'positive_sentiment' => $positiveSentiment,
                        'negative_sentiment' => $negativeSentiment,
                        'published_at' => $publishedAt,
                        'created_at' => $timestamp,
                        'updated_at' => $timestamp
                    ]);
                } catch (\Exception $e) {
                    if (!str_contains('Integrity constraint violation', $e->getMessage())) {
                        \Log::error('-----------------------------------------------------------------');
                        \Log::error($e->getMessage());
                        \Log::error(array_get($instance, 'id', 'unknown ID??'));
                    }
                }
            }

            $safetyValve++;
        }
        $end = microtime(true) - $time_start;
        $this->info('sec took - '.$end);
        \Log::alert('sec took - '.$end);
    }
}
