<?php

/**
 * Return a consistent JSON response.
 *
 * @param array $data The data to return in the response.
 * @param int $status The HTTP status code of the response.
 * @param string $message A message accompanying the response.
 * @param array $errors Any errors to include in the response.
 * @return \Illuminate\Http\JsonResponse
 */

function jsonResponse($data = [], $status = 200, $message = 'OK', $errors = []): \Illuminate\Http\JsonResponse
{
    return response()->json(compact('data', 'status', 'message', 'errors'), $status);
}
