<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseController extends Controller
{
    const TASK_STATUSES = [
        'should do',
        'done',
        'checked',
        'redo the task'
    ];

    public function sendResponseWithData($data, $code = 200)
    {
        $response = [
            'success' => true,
            'data' => $data,
        ];

        return response()->json($response, $code);
    }

    public function sendResponseWithoutData($messages = [], $code = 200)
    {
        $response = [
            'success' => true,
        ];

        if (!empty($messages)){
            $response['message'] = $messages;
        }

        return response()->json($response, $code);
    }

    public function sendError($error = [], $messages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $messages
        ];

        if (!empty($error)){
            $response['data'] = $error;
        }

        return response()->json($response, $code);
    }
}
