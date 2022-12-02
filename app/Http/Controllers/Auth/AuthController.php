<?php

namespace App\Http\Controllers\Auth;

use App\Enums\LogChannelEnum;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Exception;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    use ApiResponse;

    public function test() {
        $this->getToken();
    }

    public function getToken()
    {
        try {
            // $url = env('PAYBROKER_BASE_URL') . "v3/auth/token";
            $keys_path = storage_path('app\keys\\');
            $key_pem = "C:\laragon\www\paybrokers\storage\app\keys\pixbet-dev.pem";
            $key_key = "C:\laragon\www\paybrokers\storage\app\keys\pixbet-dev.key";
            $key_cct = "C:\laragon\www\paybrokers\storage\app\keys\cacert.pem";
            // $request = Http::withOptions(['ssl_key' => [$keys_path . 'pixbet-dev.pem', $keys_path . 'pixbet-dev.key']])
            //     ->withHeaders(['Authorization', 'Basic cGl4YmV0LWRldjpqL2xnZWpzaVVaMzE='])
            //     ->post($url);
            // return $keys_path . 'pixbet-dev.pem';


            // $client = new Client([
            //     'curl' => [
            //         CURLOPT_CAINFO => $key_cct,
            //         CURLOPT_SSLCERT => $key_pem,
            //         CURLOPT_SSLKEY => $key_key,
            //     ]
            // ]);
            // $client->request('POST', env('PAYBROKER_BASE_URL') . "v3/auth/token");
            // return $client;


            $client = new Client();
            $client->request('POST', env('PAYBROKER_BASE_URL') . "v3/auth/token", [
                'headers' => [
                    'Accept'     => 'application/json',
                    'Authorization' => 'Basic cGl4YmV0LWRldjpqL2xnZWpzaVVaMzE='
                ],
                'cert' => $key_pem,
                'ssl_key' => $key_key
            ]);
            dd($client);
            return $client;

            // return $request->send();
        } catch (Exception $e) {

            dd($e);
            return $this->Exception($e, LogChannelEnum::AUTH, 'AuthController.getToken');
        }
    }
}
