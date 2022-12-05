<?php

namespace App\Traits;

use Exception;
use App\Enums\LogChannelEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response as HttpResponse;

trait ApiResponse
{
    use LogError;

    /**
     * It returns a JSON response with a status of success, a message, and data.
     *
     * @param message The message you want to display to the user.
     * @param data The data you want to return.
     * @param code HTTP status code
     *
     * @return JsonResponse A JsonResponse object with the following structure:
     * {
     *     "status": "Success",
     *     "message": "The message",
     *     "data": []
     * }
     *
     */
    function Success($message, $data = array(), $code = HttpResponse::HTTP_OK): JsonResponse
    {
        return response()->json(
            [
                'status'    => 'Success',
                'message'   => $message,
                'data'      => $data
            ],
            $code
        );
    }

    /**
     * It returns a JSON response with a status of Error, a message, and data.
     *
     * @param message The message you want to display to the user.
     * @param data The data you want to return.
     * @param code HTTP status code
     *
     * @return JsonResponse A JsonResponse object with the following structure:
     * {
     *     "status": "Error",
     *     "message": "The message",
     *     "data": []
     * }
     *
     */
    function Error(
        $message = 'There was an error, please, try it later.',
        $data = array(),
        $code = HttpResponse::HTTP_BAD_REQUEST
    ): JsonResponse {
        return response()->json(
            [
                'status'    => 'Error',
                'message'   => $message,
                'data'      => $data
            ],
            $code
        );
    }

    /**
     * It returns a JSON response with a status of exception, a message, and data.
     *
     * @param message The message you want to display to the user.
     * @param data The data you want to return.
     * @param code HTTP status code
     *
     * @return JsonResponse A JsonResponse object with the following structure:
     * {
     *     "status": "Error",
     *     "message": "The message",
     *     "data": []
     * }
     *
     */
    function Exception(Exception $e, LogChannelEnum $channel, string $location): JsonResponse
    {
        return response()->json(
            [
                'status'    => 'Exception',
                'message'   => $this->getErrorMessage($e, $channel, $location),
                'data'      => array()
            ],
            HttpResponse::HTTP_INTERNAL_SERVER_ERROR
        );
    }

    /**
     * It returns a JSON response with a status of Validation, a message, data, and errors
     *
     * @param message The message you want to display to the user.
     * @param data The data you want to return.
     * @param errors This is an array of errors that you want to return.
     *
     * @return JsonResponse A JsonResponse object with the following structure:
     * {
     *     "status": "Error",
     *     "message": "The message",
     *     "data": [],
     *     "errors": []
     * }
     *
     */
    function Validation(string $message, $errors, array $data = array()): JsonResponse
    {
        return response()->json(
            [
                'status'    => 'Validation',
                'message'   => $message,
                'data'      => $data,
                'errors'    => $errors
            ],
            HttpResponse::HTTP_BAD_REQUEST
        );
    }
}
