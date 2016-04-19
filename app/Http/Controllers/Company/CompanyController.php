<?php

namespace App\Http\Controllers\Company;

use App\Entities\Company;
use App\Entities\Industry;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function createIndex()
    {
        \JavaScript::put([
            'industries' => Industry::orderBy('name', 'ASC')->get(['id', 'name'])
        ]);

        return view('company.create');
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