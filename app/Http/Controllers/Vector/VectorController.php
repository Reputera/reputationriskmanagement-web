<?php

namespace App\Http\Controllers\Vector;

use App\Entities\CompanyVectorColor;
use App\Http\Controllers\Controller;
use App\Entities\Vector;
use App\Transformers\Vector\VectorTransformer;
use App\Http\Requests\Vector\VectorColorRequest;

class VectorController extends Controller
{
    /**
     * @api {get} /vectors/
     * @apiName Vectors
     * @apiDescription Retrieves vector names and colors.
     * @apiGroup Vectors
     * @apiSuccessExample {json} Success-Response:
     *  HTTP/1.1 200 OK
     *  {
     *      "data": {
     *          "2016-04": {
     *              "Vector 1": 10,
     *              "Vector 2": 20,
     *              "Vector 3": 30,
     *          },
     *          "2016-7": {
     *              "Vector 1": 20,
     *              "Vector 2": 30,
     *              "Vector 3": 10,
     *          },
     *          ...
     *      },
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

    public function saveVectorColor(VectorColorRequest $request)
    {
        $vectorColor = CompanyVectorColor::firstOrNew([
            'company_id' => auth()->user()->company_id,
            'vector_id' => $request->get('vector_id')
        ]);
        $vectorColor->color = $request->get('color');
        $vectorColor->save();
        return $this->respondWithArray([]);
    }
}
