<?php

namespace App\Http\Controllers\Api\ChildrenSide;

use App\Models\Child;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Api\BaseController;
use App\Models\ShortCode;

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

    public function getToken(Request $request)
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'exists:short_codes,code']
        ]);

        $code = ShortCode::where('code', $validated['code'])->firstOrFail();

        $success = $code->child->createAccessToken();

        $code->delete();

        return $this->sendResponseWithData($success);
    }
}
