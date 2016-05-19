<?php

namespace App\Http\Controllers\Admin\Company;

use App\Entities\Industry;
use App\Http\Controllers\Controller;

class CompanyUIController extends Controller
{
    public function createIndex()
    {
        \JavaScript::put([
            'industries' => Industry::orderBy('name', 'ASC')->get(['id', 'name'])
        ]);

        return view('admin.company.create');
    }
}
