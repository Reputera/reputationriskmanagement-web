<?php

namespace App\Http\Controllers\Company;

use App\Entities\Company;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Company\NewCompanyRequest;
use App\Transformers\Company\CompanyTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CompanyController extends ApiController
{
    public function createPost(NewCompanyRequest $request)
    {
        $companies = new Collection;
        \DB::transaction(function () use ($request, $companies) {
            foreach ($request->get('companies') as $companyArray) {
                $company = Company::create($companyArray);
                $company->industries()->attach($companyArray['industry_id']);
                $companies->add($company);
            }
        });

        return $this->respondWithCollection($companies, new CompanyTransformer);
    }

    public function addCompetitor(Request $request)
    {
        $competitor = Company::findOrFail($request->input('competitor_id'));
        Company::findOrFail($request->input('company_id'))
            ->competitors()
            ->attach($competitor);
        return $this->respondWithArray([]);
    }

    public function removeCompetitor(Request $request)
    {
        $competitor = Company::findOrFail($request->input('competitor_id'));
        Company::findOrFail($request->input('company_id'))
            ->competitors()
            ->detach($competitor);
        return $this->respondWithArray([]);
    }
}