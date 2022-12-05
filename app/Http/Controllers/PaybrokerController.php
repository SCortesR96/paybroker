<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use App\Traits\GetToken;
use Illuminate\Http\Request;
use App\Enums\LogChannelEnum;
use Illuminate\Support\Facades\Http;

class PaybrokerController extends Controller
{
    use GetToken;

    private $token;

    function __construct()
    {
        $this->_baseUrl = env('PAYBROKER_BASE_URL');
        $this->_filePem = env('PAYBROKER_PEM');
        $this->_fileKey = env('PAYBROKER_KEY');
        $this->_credentials = env('PAYBROKER_CREDENTIALS');
        $this->token = $this->generateToken();
    }

    public function sendPIXPayment(Request $request)
    {
        try {
            $url = $this->_baseUrl . "v3/payment/pix";
            if ($this->token['status'] && $this->token['data']['token']) {
                if ($user = User::findByCPF($request->cpf)) {
                    $paymentRequest = Http::withHeaders([
                        'Accept'        => 'application/json',
                        'Authorization' => 'Bearer ' . $this->token['data']['token']
                    ])->post(
                        $url,
                        [
                            'value' => "$request->value",
                            'description'   => "$request->description",
                            'webhook_url'   => "$request->webhook_url",
                            'buyer' => [
                                'cpf'   => $user->cpf,
                                'name'  => $user->name,
                                'email' => $user->email,
                            ]
                        ]
                    );

                    $response = json_decode($paymentRequest->getBody());

                    if ($response->id) {
                        return $this->Success(
                            'Payment information created successfully.',
                            $response
                        );
                    }
                }
                return $this->Error('User not found');
            }
            return $this->Error();
        } catch (Exception $e) {
            return $this->Exception(
                $e,
                LogChannelEnum::PAYMENT,
                'PaybrokerController.getPIXPayment'
            );
        }
    }

    // public function webhook()
    // {
    //     $endpoint_secret = 'whsec_a4d46a79d4df8c2da2ebc40b0f6f72b0437545aebcb262eb2b19919754f22b97';

    //     $payload = @file_get_contents('php://input');
    //     $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
    //     $event = null;

    //     try {
    //         $event = Webhook::constructEvent(
    //             $payload,
    //             $sig_header,
    //             $endpoint_secret
    //         );
    //     } catch (\UnexpectedValueException $e) {
    //         // Invalid payload
    //         http_response_code(400);
    //         exit();
    //     } catch (Exception $e) {
    //         // Invalid signature
    //         http_response_code(400);
    //         exit();
    //     }

    //     // Handle the event
    //     echo 'Received unknown event type ' . $event->type;

    //     http_response_code(200);
    // }
}
