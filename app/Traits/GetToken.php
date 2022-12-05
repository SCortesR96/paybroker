<?php

namespace App\Traits;

use Exception;
use GuzzleHttp\Client;
use App\Enums\LogChannelEnum;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

trait GetToken
{
    use ApiResponse, LogError, BasicResponses;

    function __construct()
    {
        $this->_baseUrl = env('PAYBROKER_BASE_URL');
        $this->_filePem = env('PAYBROKER_PEM');
        $this->_fileKey = env('PAYBROKER_KEY');
        $this->_credentials = env('PAYBROKER_CREDENTIALS');
    }

    /**
     * It's a function that generates a token for the API
     *
     * @return array An array with the status and data.
     */
    public function generateToken(): array
    {
        try {
            $keys_path = storage_path('app\keys\\');
            $url = $this->_baseUrl . "v3/auth/token";

            $request = Http::retry(3)->withOptions([
                'cert' => $keys_path . $this->_filePem,
                'ssl_key' => $keys_path . $this->_fileKey
            ])->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $this->_credentials
            ])->post($url);

            $body = json_decode($request->getBody());
            if ($body->token) {
                return $this->basicResponse(
                    true,
                    ['token' => $body->token]
                );
            }
            return $this->basicResponse(
                false,
                array()
            );
        } catch (Exception $e) {
            return $this->basicResponse(
                false,
                [
                    'error' => $this->getErrorMessage($e, LogChannelEnum::AUTH, 'AuthController.getToken')
                ]
            );
        }
    }
}
