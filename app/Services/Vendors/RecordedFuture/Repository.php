<?php

namespace App\Services\Vendors\RecordedFuture;

use App\Entities\Company;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Repository
{
    /** @var string */
    protected $error = '';

    /**
     * Gets the error message that was generated from the insert attempt.
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Attempts to save an instance for a given company.
     *
     * @param Instance $instance
     * @param Company $company
     * @return bool
     */
    public function saveInstanceForCompany(Instance $instance, Company $company)
    {
        $this->resetError();

        $fragment = $instance->getFragment();
        $hashedFragment = sha1($fragment);
        $attributes = $instance->getAttributes();
        $document = $instance->getDocument();
        $link = $document->getUrl();
        $hashedLink = sha1($link);

        $fragmentDupeQuery = DB::table('instances')->select(['id'])
            ->where('fragment_hash', $hashedFragment)
            ->where('company_id', $company->id);
        $instanceDupeExists = DB::table('instances')->where('link_hash', $hashedLink)
            ->where('company_id', $company->id)
            ->union($fragmentDupeQuery)
            ->select(['id'])
            ->first();

        if ($instanceDupeExists) {
            $this->error = 'Duplicate Record: '.$instance->__toString();
            return false;
        }

        if ($attributes->getPositiveSentiment() == $attributes->getNegativeSentiment()) {
            $this->error = 'Nullifed sentiment: '.$instance->__toString();
            return false;
        }

        $timestamp = (new Carbon())->toDateTimeString();
        $positiveScore = round($attributes->getPositiveSentiment() * 100);
        $negativeScore = round($attributes->getNegativeSentiment() * 100);

        try {
            $instanceId = DB::table('instances')->insertGetId([
                'company_id' => $company->id,
                'vector_id' => DB::table('vector_event_types')->where('event_type', $instance->getType())->value('vector_id'),
                'entity_id' => $instance->getId(),
                'type' => $instance->getType(),
                'start' => (new Carbon($instance->getStart()))->toDateTimeString(),
                'language' => $document->getLanguage(),
                'source' => $document->getSource()->getName(),
                'title' => $document->getTitle(),
                'fragment' => $fragment,
                'fragment_hash' => $hashedFragment,
                'link' => $link,
                'link_hash' => $hashedLink,
                'risk_score' => (-$negativeScore + $positiveScore),
                'positive_risk_score' => $positiveScore,
                'negative_risk_score' => $negativeScore,
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

            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
        }

        return false;
    }

    /**
     * Resets the error message to it's original state.
     */
    protected function resetError()
    {
        $this->error = '';
    }
}
