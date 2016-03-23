<?php

namespace App\Http\Requests;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class Request extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [];
    }

    /**
     * @return \App\Entities\User
     */
    public function user($guard = null)
    {
        return parent::user();
    }

    /**
     * Send a query builder through specified pipelines.
     *
     * @param $builder
     * @param array $pipelines
     * @return Builder
     */
    public function sendBuilderThroughPipeline(Builder $builder, array $pipelines)
    {
        $pipeline = app('Illuminate\Pipeline\Pipeline');
        $pipeline->send($this);
        $pipeline->through($pipelines);
        return $pipeline->then(function ($request) use ($builder) {
            return $builder;
        });
    }

    public function getForQuery(array $params)
    {
        $returnArray = [];
        foreach($params as $param) {
            if($paramValue = $this->get($param)) {
                $returnArray[str_replace('_', '.', $param)] = $paramValue;
            }
        }
        return array_filter($returnArray);
    }
}
