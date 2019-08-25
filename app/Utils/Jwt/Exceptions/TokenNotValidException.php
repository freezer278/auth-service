<?php


namespace App\Utils\Jwt\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenNotValidException extends Exception implements Responsable
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param Request $request
     * @return Response
     */
    public function toResponse($request)
    {
        return \response()->json('Invalid auth token', 401);
    }
}
