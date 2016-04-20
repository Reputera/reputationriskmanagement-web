<?php

namespace App\Http\Controllers\Company;

use App\Entities\Company;
use App\Entities\Industry;
use App\Http\Controllers\Controller;
use App\Http\Requests\Company\NewCompanyRequest;
use Illuminate\Http\Request;

class CompanyUIController extends Controller
{
    public function createIndex()
    {
        \JavaScript::put([
            'industries' => Industry::orderBy('name', 'ASC')->get(['id', 'name'])
        ]);

        return view('company.create');
    }
}
