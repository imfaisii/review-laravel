<?php
namespace App\Traits\Common;

use Symfony\Component\HttpFoundation\JsonResponse;

trait HasApiResponses
{
    /**
     * Generate a success response.
     *
     * @param  array|string|null  $data
     * @param  int  $statusCode
     * @param  string|null  $message
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse($data = null, $statusCode = 200, $message = "success"): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Generate an error response.
     *
     * @param  string|null  $message
     * @param  int  $statusCode
     * @param  array|null  $errors
     * @return \Illuminate\Http\JsonResponse
     */
    public function errorResponse($message = null, $statusCode = 400, $errors = []): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ];

        return response()->json($response, $statusCode);
    }

    /**
     * Generate an exception response.
     *
     * @param  \Exception  $exception
     * @param  int  $statusCode
     * @return \Illuminate\Http\JsonResponse
     */
    public function exceptionResponse(\Exception $exception, $statusCode = 500): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => 'An exception occurred.',
            'exception' => $exception->getMessage(),
        ];

        return response()->json($response, $statusCode);
    }
}
