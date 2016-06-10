<?php

namespace App\Http\Controllers\Admin\Company;

use App\Entities\Company;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Company\NewCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyLogoRequest;
use App\Jobs\QueueYearlyRecordedFutureInstances;
use App\Jobs\YearlyRecordedFutureInstances;
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
                $companyArray = array_merge($companyArray, [
                    'min_threshold' => config('rrm.default_alerts.min_threshold'),
                    'max_threshold' => config('rrm.default_alerts.max_threshold')
                ]);
                $company = Company::create($companyArray);
                $company->industries()->attach($companyArray['industry_id']);
                $companies->add($company);
            }
        });

        // Did this to allow the DB transaction to complete since the command will look up the entity_id to use.
        foreach ($companies as $company) {
            $this->dispatch(new QueueYearlyRecordedFutureInstances($company));
        }

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

    /**
     * @api {get} /company/logo Company Logo
     * @apiName CompanyLogo
     * @apiDescription Return the current company's logo image.
     * @apiGroup Company
     */
    public function getCompanyLogo(Request $request)
    {
        $company = Company::findOrFail($request->input('company_id'));
        if(file_exists($company->logo_filename)) {
            return response()->download($company->logo_filename);
        }
        return response()->download(storage_path() . '/' . config('rrm.filesystem.logo.default'));
    }

    public function updateCompanyLogo(UpdateCompanyLogoRequest $request)
    {
        $company = Company::findOrFail($request->get('company_id'));
        if(file_exists(config('rrm.filesystem.logo.directory') . $company->logo_filename)) {
            file(config('rrm.filesystem.logo.directory') . $company->logo_filename)->delete();
        }
        $logoFile = $request->file('logoImage');
        $filename = base64_encode(\Hash::make($company->id . time() . $logoFile->getClientOriginalName())) . '.' . $logoFile->getClientOriginalExtension();

        $company->logo_filename = $filename;
        $company->save();
        $logoFile->move(
            storage_path(config('rrm.filesystem.logo.directory')),
            $filename
        );
    }

}