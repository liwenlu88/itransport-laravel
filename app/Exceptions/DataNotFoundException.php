<?php

namespace App\Exceptions;

use Exception;

class DataNotFoundException extends Exception
{
    public function __construct($message = '不存在或已删除')
    {
        parent::__construct($message, 403);
    }

    public function render($request)
    {
        return response()->json([
            'code' => 404,
            'message' => $this->getMessage()
        ], 404);
    }
}
