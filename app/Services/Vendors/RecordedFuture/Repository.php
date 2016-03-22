<?php

namespace App\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Repository
{
    protected $error = null;

    public function getError()
    {
        return $this->error;
    }

    public function saveInstanceForCompany(Instance $instance, Company $company)
    {
        $this->resetError();

        $fragment = $instance->getFragment();
        $hashedFragment = md5($fragment);
        $attributes = $instance->getAttributes();

        if (DB::table('instances')->where('fragment_hash', $hashedFragment)->value('id') ||
            $attributes->getPositiveSentiment() == $attributes->getNegativeSentiment()
        ) {
            return false;
        }

        $vectorId = DB::table('vector_event_types')->where('event_type', $instance->getType())->value('vector_id');

        $timestamp = (new Carbon())->toDateTimeString();
        $document = $instance->getDocument();
        
        try {
            $instanceId = DB::table('instances')->insertGetId([
                'company_id' => $company->id,
                'vector_id' => $vectorId,
                'entity_id' => $instance->getId(),
                'type' => $instance->getType(),
                'start' => (new Carbon($instance->getStart()))->toDateTimeString(),
                'language' => $document->getLanguage(),
                'source' => $document->getSource()->getName(),
                'title' => $document->getTitle(),
                'fragment' => $fragment,
                'fragment_hash' => md5($fragment),
                'link' => $document->getUrl(),
                'sentiment' => (-$attributes->getNegativeSentiment() + $attributes->getPositiveSentiment()),
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

    protected function resetError()
    {
        $this->error = null;
    }
}
