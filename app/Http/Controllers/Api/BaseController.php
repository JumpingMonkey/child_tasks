<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TaskIcon;
use Illuminate\Http\Request;
use Awcodes\Curator\Models\Media;

class BaseController extends Controller
{
    const TASK_STATUSES = [
        'should do',
        'redo',
        'done',
        'checked',
    ];

    const TASK_STATUSES_FOR_CHILDREN = [
        'done',
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

    public function getDefaultIcon()
    {
        return $this->sendResponseWithData(TaskIcon::first());
    }

    public function getTaskIcons()
    {
        return $this->sendResponseWithData(Media::all());
    }

    public function getTaskIconById(Media $icon)
    {
        return $this->sendResponseWithData($icon);
    }

    public function getTaskStatuses()
    {
        return $this->sendResponseWithData(self::TASK_STATUSES);
    }
}
