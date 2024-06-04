<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function sendResponse($data = 'empty', $message = '', $extra = []) 
    {
        $response = [
            'status' => Response::HTTP_OK,
            'message' => $message,
        ];

        $response = array_merge($response, $extra);
        if ( $data !== 'empty' ) {
            $response['data'] = $data;
        }

        return response()->json($response, Response::HTTP_OK);
    }

    protected function sendError($message, $errorMessages = [], $status = Response::HTTP_BAD_REQUEST, $errors = [])
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if ( !empty($errorMessages) ) {
            $response['data'] = $errorMessages;
        }

        if ( !empty($errors) ) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status);
    }
}
