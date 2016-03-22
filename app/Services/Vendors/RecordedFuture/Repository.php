<?php

namespace App\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Repository
{
    protected $error;

    public function getError()
    {
        return $this->error;
    }

    public function saveInstanceForCompany(Instance $instance, Company $company)
    {
        try {
            $vectorId = null;
            $vectorEventType = DB::table('vector_event_types')->where('event_type', $instance->getType())
                ->first(['vector_id']);
            if ($vectorEventType) {
                $vectorId = $vectorEventType->vector_id;
            };

            $timestamp = (new Carbon())->toDateTimeString();
            $fragment = $instance->getFragment();
            $document = $instance->getDocument();
            $attributes = $instance->getAttributes();

            $publishedAt = null;
            if ($documentPublishedAt = $document->getPublished()) {
                $publishedAt = (new Carbon($documentPublishedAt))->toDateTimeString();
            }

            $instanceId = DB::table('instances')->insertGetId([
                'company_id' => $company->id,
                'vector_id' => $vectorId,
                'entity_id' => $instance->getId(),
                'type' => $instance->getType(),
                'start' => $publishedAt,
                'language' => $document->getLanguage(),
                'source' => $document->getSource()->getName(),
                'title' => $document->getTitle(),
                'fragment' => $fragment,
                'fragment_hash' => md5($fragment),
                'link' => $document->getUrl(),
                'positive_sentiment' => $attributes->getPositiveSentiment(),
                'negative_sentiment' => $attributes->getNegativeSentiment(),
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);

            $countryEntityIds = array_map(function ($entity) {
                return $entity->getId();
            }, $instance->getCountries());

            if ($instanceId && $countryEntityIds) {
                $countries = DB::table('countries')->whereIn('entity_id', $countryEntityIds)->get(['id']);
                foreach ($countries as $country) {
                    \DB::table('instance_country')->insert([
                        'instance_id' => $instanceId,
                        'country_id' => $country->id
                    ]);
                }
            }
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
