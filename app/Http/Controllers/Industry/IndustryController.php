<?php

namespace App\Http\Controllers\Industry;

use App\Entities\Industry;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Industry\NewIndustryRequest;

class IndustryController extends ApiController
{
    public function store(NewIndustryRequest $request)
    {
        $industry = Industry::create(['name' => $request->get('industry_name')]);

    }
}
