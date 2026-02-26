<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;

class Oauth extends BaseController
{
    protected $oauthBaseURL;
    protected $userModel;

    public function __construct()
    {
        $this->oauthBaseURL = env('ACCESS_URL');

        $this->userModel    = new UserModel();

        date_default_timezone_set('Asia/Jakarta');
    }

    public function callback()
    {
        $code = $this->request->getGet('code');

        if (!$code) {
            return redirect()->to('/login');
        }

        $tokenExchangeURL = $this->oauthBaseURL . "/oauth/token";
        $client = \Config\Services::curlrequest();

        try {

            $response = $client->post($tokenExchangeURL, [
                'json' => [
                    'grant_type'    => 'authorization_code',
                    'code'          => $code,
                    'client_id'     => env('ACCESS_CLIENT_ID'),
                    'redirect_uri'  => env('ACCESS_REDIRECT_URI'),
                    'client_secret' => env('ACCESS_CLIENT_SECRET'),
                ],
                'http_errors' => false,
            ]);

            $status = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);

            if (
                $status === 200 &&
                isset($responseBody['data']['access_token'])
            ) {
                $accessToken = $responseBody['data']['access_token'];

                session()->set('access_token', $accessToken);

                $meURL = $this->oauthBaseURL . "/oauth/me";

                $meResponse = $client->get($meURL, [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                    'http_errors' => false,
                ]);

                $meData = json_decode($meResponse->getBody(), true);
                $user   = $meData['data'];

                // echo "<pre>";
                // var_dump([
                //     'token_exchange_status' => $status,
                //     'token_exchange_body'   => $responseBody,
                //     'me_status'             => $meResponse->getStatusCode(),
                //     'me_body'               => $user,
                //     'me_raw'                => (string) $meResponse->getBody(),
                //     'token_used'            => $accessToken,
                // ]);
                // echo "</pre>";

                $getUser = $this->userModel->GetUserByEmail($user['username']);

                if (empty($getUser)) {
                    $this->userModel->InsertUser($user);
                }

                $getUser = $this->userModel->GetUserByEmail($user['username']);
                session()->set('user', $getUser);

                return redirect()->to('/');
            }

            return redirect()->to('/login');

        } catch (\Throwable $e) {

            echo "<pre>";
            var_dump([
                'exception' => get_class($e),
                'message'   => $e->getMessage(),
            ]);
            echo "</pre>";
            die();
        }
    }
}