<?php

namespace App\Http\Controllers\Admin\Company;

use App\Entities\Company;
use App\Entities\Industry;
use App\Entities\Vector;
use App\Http\Controllers\Controller;
use App\Transformers\Company\CompanyTransformer;
use App\Transformers\Vector\VectorTransformer;

class CompanyUIController extends Controller
{
    public function createIndex()
    {
        \JavaScript::put([
            'industries' => Industry::orderBy('name', 'ASC')->get(['id', 'name'])
        ]);

        return view('admin.company.create');
    }

    public function editIndex() {
        \JavaScript::put([
            'companies' => $this->fractalizeCollection(Company::all(), new CompanyTransformer()),
            'vectors' => $this->fractalizeCollection(Vector::all(), new VectorTransformer())
        ]);

        return view('admin.company.edit');
    }
}
