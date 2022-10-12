<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Cache;

class MultipleUserLoginException extends Exception
{
    protected $message;

    public function __construct($message, $externalToken, $statusCode = null)
    {
        $this->message = $message;
        Cache::forget($externalToken);
    }

    public function render($request)
    {
        return response()->json(["status" => "error", "message" => $this->getMessage()]);       
    }
}
