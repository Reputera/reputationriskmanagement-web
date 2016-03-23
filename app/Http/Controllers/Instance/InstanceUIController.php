<?php

namespace App\Http\Controllers\Instance;


use App\Entities\Company;
use App\Entities\Vector;
use App\Http\Controllers\Controller;

class InstanceUIController extends Controller
{

    public function index()
    {
        \JavaScript::put([
            'vectors' => Vector::all(),
            'companies' => Company::all()
        ]);
        return view('instance.instanceQuery');
    }

}