<?php

namespace App\Http\Controllers\Api\Parent;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\AttachCode;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ParentsChildrenController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $children = $request->user()
            ->children()
            ->get();
        
        return $this->sendResponseWithData($children);
    }

    public function detach(Request $request, User $child)
    {
        if(!$child->user_id){
            return $this->sendError('', 'You can not do it sir', 404);
        }
        Gate::authorize('is_real_parent', $child->user_id);
        
        $child->parent()->disassociate();
        $child->save();

        return $this->sendResponseWithoutData('Child was detached!');
    }

    public function generateAttachCode(Request $request)
    {
        $code = $request->user()->code()->first();
        if(!$code){
            $code = AttachCode::create([
                'user_id' => $request->user()->id,
                'code' => random_int(10, 99) . $request->user()->id,
            ]);
        }

        return $this->sendResponseWithData($code);
               
    }
}
