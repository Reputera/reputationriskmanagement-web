<?php

namespace App\Http\Controllers\Admin\Industry;

use App\Entities\Industry;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Industry\NewIndustryRequest;
use App\Transformers\Industry\IndustryTransformer;

class IndustryController extends ApiController
{
    public function store(NewIndustryRequest $request)
    {
        return $this->respondWithItem(
            Industry::create(['name' => $request->get('industry_name')]),
            new IndustryTransformer
        );
    }
}
