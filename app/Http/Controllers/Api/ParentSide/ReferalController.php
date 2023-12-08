<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\ReferalCodeRequest;
use App\Models\Adult;
use Illuminate\Http\Request;
use App\Models\ReferalCode;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class ReferalController extends BaseController
{
    public function createCode(Request $request)
    {
        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }

        $code = $request->user()->createdReferalCodes()->firstOrCreate([], [
            'code' => $request->user()->id . Str::random(6) . random_int(10,99),
        ]);
        $loginedFriends = Adult::query()->whereHas('usedReferalCodes', function($q) use($request){
            $q->where('referal_codes.adult_id', $request->user()->id);
        })->count();

        $usedCodes = ReferalCode::where('adult_id', $request->user()->id)
            ->has('adultsWhoUsed')
            ->get();

        $responce = [
            'code' => $code,
            'friends invited' => $loginedFriends,
            'days received' => $loginedFriends * 7,
            'used_referal_codes' => $usedCodes
        ];
        return $this->sendResponseWithData($responce,200);
    }

    public function useReferalCode(ReferalCodeRequest $request)
    {
        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }

        $code = $request->validated();

        $adult = $request->user();

        $referalCode = ReferalCode::where('code', $code)->firstOrFail();

        if($referalCode->adult_id == $adult->id){
            return $this->sendError([], "You can't use your own code!", 401);
        }
        
        if($adult->usedReferalCodes->doesntContain($referalCode->id)){
            $adult->usedReferalCodes()->attach($referalCode, ['created_at' => now()]);
            if($adult->is_premium){
                $adult->until = $adult->until ? $adult->until->addDays(7): now()->addDays(7);
                $adult->save();
            } else {
                $adult->is_premium = true;
                $adult->until = now()->addDays(7);
                $adult->save();
            }

            $creator = $referalCode->adultCreator;
            $creator->until = $creator->until ? $creator->until->addDays(7) : now()->addDays(7);
            $creator->save();
            
            return $this->sendResponseWithData($adult->load('usedReferalCodes'),200);

        } else {
            $adult->usedReferalCodes()->updateExistingPivot($referalCode->id, ['updated_at' => now()]);

            return $this->sendError([], "You can't use one code twice!", 401);
        } 
    }
}
