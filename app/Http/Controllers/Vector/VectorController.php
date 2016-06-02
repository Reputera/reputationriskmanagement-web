<?php

namespace App\Http\Controllers\Vector;

use App\Entities\Company;
use App\Entities\CompanyVectorColor;
use App\Http\Controllers\Controller;
use App\Entities\Vector;
use App\Http\Requests\Request;
use App\Http\Requests\Vector\AdminVectorColorRequest;
use App\Transformers\Vector\VectorTransformer;
use App\Http\Requests\Vector\VectorColorRequest;

class VectorController extends Controller
{
    /**
     * @api {get} /vectors/ List Vectors
     * @apiName Vectors
     * @apiDescription Retrieves vector names and colors.
     * @apiGroup Vectors
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data":[
     *          {"id":1,"name":"Social Responsibility","color":"#4BE6A1","default_color":"#4BE6A1"},
     *          {"id":2,"name":"Influencers","color":"#4BE6A1","default_color":"#4BE6A1"}
     *      ],
     *      "status_code": 200,
     *      "message": "Success"
     *  }
     */
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function get()
    {
        return $this->respondWith(Vector::all(), new VectorTransformer());
    }

    public function getCompanyVectorColors(Request $request)
    {
        $colors = \DB::table('company_vector_colors')
            ->join('companies', 'company_vector_colors.company_id', '=', 'companies.id')
            ->join('vectors', 'company_vector_colors.vector_id', '=', 'vectors.id')
            ->where('company_id', $request->get('company_id'))
            ->select('vectors.name as vector_name', 'vectors.default_color as default_color', 'vectors.id as vector_id', 'color')
            ->get();

        return $this->respondWithArray($colors);
    }

    public function updateVectorColor($companyId, $vectorId, $color)
    {
        $vectorColor = CompanyVectorColor::firstOrNew([
            'company_id' => $companyId,
            'vector_id' => $vectorId
        ]);
        $vectorColor->color = $color;
        return $vectorColor->save();
    }

    public function saveVectorColor(VectorColorRequest $request)
    {
        $this->updateVectorColor(auth()->user()->company_id, $request->get('vector_id'), $request->get('color'));
        return $this->respondWith(Vector::find($request->get('vector_id')), new VectorTransformer());
    }

    public function adminSaveVectorColor(AdminVectorColorRequest $request)
    {
        $this->updateVectorColor($request->get('company_id'), $request->get('vector_id'), $request->get('color'));
        return $this->respondWith(Vector::find($request->get('vector_id')), new VectorTransformer());
    }
}
