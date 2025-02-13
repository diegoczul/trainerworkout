<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Setup the layout used by the controller.
     *
     * @return void
     */
    protected function setupLayout(): void
    {
        if (!is_null($this->layout)) {
            $this->layout = view($this->layout);
        }
    }

    protected function responseJson($content, int $statusCode = 200, string $type = 'json'): JsonResponse
    {
        return response()->json($content, $statusCode);
    }

    protected function responseJsonError($content, int $statusCode = 400, string $type = 'json'): JsonResponse
    {
        return response()->json($content, $statusCode);
    }

    protected function responseJsonErrorValidation($messages, int $statusCode = 400, string $type = 'text'): JsonResponse
    {
        $responseType = $this->getResponseType($type);
        $output = "<ul>";
        foreach ($messages->all('<li>:message</li>') as $message) {
            $output .= $message;
        }
        $output .= "</ul>";
        return response()->json($output, $statusCode);
    }

    protected function _getStatusCodeMessage(int $status): string
    {
        $codes = [
            200 => 'OK',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
        ];
        return $codes[$status] ?? '';
    }

    private function isJson($string): bool
    {
        if (is_array($string)) {
            return false;
        }
        json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    private function getResponseType(string $type): string
    {
        return match ($type) {
            'text' => 'text/plain',
            'json' => 'application/json',
            default => 'text/plain',
        };
    }

    public function checkPermissions($user, $user2): bool
    {
        return $user === $user2;
    }
}
