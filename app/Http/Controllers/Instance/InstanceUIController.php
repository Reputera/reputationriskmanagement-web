<?php

namespace App\Http\Controllers\Instance;

use App\Entities\Company;
use App\Entities\Region;
use App\Entities\User;
use App\Entities\Vector;
use App\Http\Controllers\ApiController;

class InstanceUIController extends ApiController
{

    public function index()
    {
        \JavaScript::put([
            'vectors' => Vector::all(),
            'companies' => Company::all(),
            'regions' => Region::all()
        ]);
        return view('instance.instanceQuery');
    }

    public function sentimentIndex()
    {
        \JavaScript::put([
            'vectors' => Vector::all(),
            'competitors' => auth()->user()->company->competitors,
            'regions' => Region::all()
        ]);
        return view('instance.sentimentQuery');
    }
}
