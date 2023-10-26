<?php

namespace App\Http\Controllers\Api\ChildrenSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Child;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ChildInfoController extends BaseController
{
    public function getMainInfo(Request $request)
    {
        if(! Gate::allows('is_child_model', $request->user())){
            abort(403, 'You should be a child!');
        }
        $child = $request->user()->load('adults');
        return $this->sendResponseWithData($child, 200);
    }
}
