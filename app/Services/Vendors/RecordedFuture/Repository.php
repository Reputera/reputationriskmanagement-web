<?php

namespace App\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use App\Entities\Vector;
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
            $countryId = null;
            $regionId = null;
            if ($countryName = $instance->getCountry()) {
                $countryInfo = DB::table('countries')->where('name', $countryName)->first(['id', 'region_id']);
                if ($countryInfo) {
                    $countryId = $countryInfo->id;
                    $regionId = $countryInfo->region_id;
                }
            }

            $vectorId = null;
            if ($vector = Vector::fromEventType($instance->getType())) {
                $vectorId = $vector->id;
            };

            $timestamp = (new Carbon())->toDateTimeString();
            $fragment = $instance->getFragment();
            $document = $instance->getDocument();
            $attributes = $instance->getAttributes();

            $publishedAt = null;
            if ($documentPublishedAt = $document->getPublished()) {
                $publishedAt = (new Carbon($documentPublishedAt))->toDateTimeString();
            }

            DB::table('instances')->insert([
                'company_id' => $company->id,
                'vector_id' => $vectorId,
                'country_name' => $countryName,
                'country_id' => $countryId,
                'region_id' => $regionId,
                'entity_id' => $instance->getId(),
                'event_type' => $instance->getType(),
                'original_language' => $document->getLanguage(),
                'source' => $document->getSource()->getName(),
                'title' => $document->getTitle(),
                'fragment' => $fragment,
                'fragment_hash' => md5($fragment),
                'link' => $document->getUrl(),
                'positive_sentiment' => $attributes->getPositiveSentiment(),
                'negative_sentiment' => $attributes->getNegativeSentiment(),
                'published_at' => $publishedAt,
                'created_at' => $timestamp,
                'updated_at' => $timestamp
            ]);
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }
    }
}
