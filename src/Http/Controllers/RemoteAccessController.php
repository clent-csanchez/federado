<?php

namespace Clent\Federado\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Auth};

class RemoteAccessController {
    
    public function __construct(
        protected Client $client 
    )
    {
        
    }

    public function __invoke(Request $request)
    {
        $user_secret = $this->getUserSecret($request);

        $user = $this->getLocalUser($user_secret);

        if (!$user) return redirect('/login');

        if(Auth::check()) Auth::logout();

        Auth::loginUsingId($user->user_id);

        $redirect_url  = config('federado.redirect');

        return $redirect_url;
    }

    private function getUserSecret($request)
    {
        $headers = $this->getHeaders($request);
        
        $url = config('federado.http.url');

        dd($headers,$url);
        try {
            $response = $this->client->post($url,$headers);
            return json_decode($response->getBody())->user_secret;
        } catch (\Throwable $th) {
            throw $th;
        }  
    }

    private function getHeaders($request)
    {
        $secret = config('federado.sso_secret');

        return [
            'headers' => [
                'Accept'       => 'Application/json',
                'Content-type' => 'Application/json',
            ],
            'query' => [
                'token_generated'   => $request->get('token'),
                'plataform_id'      => config('federado.sso_plataform_id'),
                'secret'            => bcrypt($secret)
            ],
            'timeout' => 10
        ];
    }

    private function getLocalUser(string $user_secret)
    {
        return DB::table('users')
            ->join('tb_user_secret', 'users.id', '=', 'tb_user_secret.user_id')
            // ->where('project' ,self::CARDIF)
            ->where('tb_user_secret.secret', '=', $user_secret)
            ->first();
    }
}