<?php

namespace App\Http\Controllers\Auth;

use Exception;
use GuzzleHttp\Client;
use App\Traits\GetToken;
use App\Traits\ApiResponse;
use App\Enums\LogChannelEnum;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    use ApiResponse, GetToken;
    private $_baseUrl;
    private $_filePem;
    private $_fileKey;
    private $_credentials;

    function __construct()
    {
        $this->_baseUrl = env('PAYBROKER_BASE_URL');
        $this->_filePem = env('PAYBROKER_PEM');
        $this->_fileKey = env('PAYBROKER_KEY');
        $this->_credentials = env('PAYBROKER_CREDENTIALS');
    }

    public function getToken()
    {
        try {
            $keys_path = storage_path('app\keys\\');
            $url = $this->_baseUrl . "v3123/au123th/token";

            $request = Http::retry(3)->withOptions([
                'cert' => $keys_path . $this->_filePem,
                'ssl_key' => $keys_path . $this->_fileKey
            ])->withHeaders([
                'Accept' => 'application/json',
                'Authorization' => 'Basic ' . $this->_credentials
            ])->post($url);

            $body = json_decode($request->getBody());

            if ($body->token) {
                return $this->Success('Token generated successfully.', $body);
            }

            return $this->Error();
        } catch (Exception $e) {
            return $this->Exception($e, LogChannelEnum::AUTH, 'AuthController.getToken');
        }
    }

    public function showToken()
    {
        return $this->generateToken();
    }
}
