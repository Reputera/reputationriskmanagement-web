<?php

namespace App\Http\Controllers\Admin\Company;

use App\Entities\Company;
use App\Http\Controllers\ApiController;
use App\Http\Requests\Company\NewCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyLogoRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Jobs\QueueYearlyRecordedFutureInstances;
use App\Jobs\YearlyRecordedFutureInstances;
use App\Transformers\Company\CompanyTransformer;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CompanyController extends ApiController
{

    public function getCompany($companyId)
    {
        return $this->respondWith(Company::findOrFail($companyId), new CompanyTransformer());
    }

    public function getAllCompanies()
    {
        return $this->respondWith(Company::all(), new CompanyTransformer());
    }

    public function updateCompany($companyId, UpdateCompanyRequest $request)
    {
        $params = array_filter($request->only([
            'min_alert_threshold',
            'max_alert_threshold'
        ]));
        $company = Company::findOrFail($companyId);
        $company->update($params);
        return $this->respondWith($company, new CompanyTransformer());
    }

    public function createPost(NewCompanyRequest $request)
    {
        $companies = new Collection;
        \DB::transaction(function () use ($request, $companies) {
            foreach ($request->get('companies') as $companyArray) {
                $companyArray = array_merge($companyArray, [
                    'min_alert_threshold' => config('rrm.default_alerts.min_alert_threshold'),
                    'max_alert_threshold' => config('rrm.default_alerts.max_alert_threshold')
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
    public function getCurrentCompanyLogo(Request $request)
    {
        $company = $request->user()->company;
        if(file_exists($company->logo_filename)) {
            return response()
                ->download($company->logo_filename, null, ['Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate']);
        }
        return response()
            ->download(
                storage_path() . '/' . config('rrm.filesystem.logo.default'),
                null,
                ['Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate']
            );
    }

    public function getCompanyLogo(Request $request)
    {
        $company = Company::find($request->input('company_id'));
        if(file_exists($company->logo_filename)) {
            return response()
                ->download($company->logo_filename, null, ['Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate']);
        }
        return response()
            ->download(
                storage_path() . '/' . config('rrm.filesystem.logo.default'),
                null,
                ['Cache-Control' => 'no-cache, no-store, max-age=0, must-revalidate']
            );
    }

    public function updateCompanyLogo(UpdateCompanyLogoRequest $request)
    {
        $company = Company::findOrFail($request->get('company_id'));
        if(file_exists($company->logo_filename)) {
            \File::delete($company->logo_filename);
        }

        $logoFile = $request->file('logoImage');
        $filename = base64_encode(\Hash::make($company->id . time() . $logoFile->getClientOriginalName())) . '.' . $logoFile->getClientOriginalExtension();

        $company->logo_filename = config('rrm.filesystem.logo.directory') .$filename;
        $company->save();
        $logoFile->move(
            config('rrm.filesystem.logo.directory'),
            $filename
        );
    }


}