<?php

namespace App\Http\Controllers\Api\ParentSide;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Adult;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class StatisticController extends BaseController
{
    public function adultStatistic(Request $request)
    {
        if(!Gate::allows('is_adult_model', $request->user())){
            abort(403,'You are not adult!');
        }

        $adult = Adult::withCount([
            'regularTaskTemplates' => fn( $q) => $q->whereHas('regularTask', fn($q) => $q->where('status', 'checked')),
            'oneDayTasks' => fn(Builder $q) => $q->where('status', 'checked'),
            'rewards' => function(Builder $q){
                $q->where('is_received', 1);
            }
        ])
        ->where('id', $request->user()->id)
        ->firstOrFail();

        return $this->sendResponseWithData($adult);
    }
}
