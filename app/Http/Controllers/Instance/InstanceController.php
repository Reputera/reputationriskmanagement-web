<?php

namespace App\Http\Controllers\Instance;

use App\Entities\Instance;
use App\Http\Controllers\Controller;
use App\Http\Requests\Request;

class InstanceController extends Controller
{

    public function flag(Request $request)
    {
        Instance::where('id', $request->get('id'))
            ->withTrashed()
            ->firstOrFail()
            ->toggleTrashed();
    }
}
