<?php

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class CustomResponse extends JsonResponse
{
    public function __construct($data = null, $status = 200, $headers = [], $options = 0)
    {
        $responseData = [
            'status' => $status,
            'data' => $data
        ];

        parent::__construct($responseData, $status, $headers, $options);
    }
}