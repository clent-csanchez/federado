<?php

namespace Clent\Federado\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB,Auth};

class RemoteAccessController {
    
    protected $client;
    
    public function __construct()
    {
        $this->client = new Client();
    }

    public function __invoke(Request $request)
    {
        $user_secret = $this->getUserSecret($request);

        $user = $this->getLocalUser($user_secret);

        if (!$user) return redirect('/login');

        if(Auth::check()) Auth::logout();

        Auth::loginUsingId($user->user_id);

        $redirect_url  = config('federado.redirect');

        if ($redirect_url){
            return redirect($redirect_url);
        }

        return redirect()->intended();
    }

    private function getUserSecret($request)
    {
        $headers = $this->getHeaders($request);
        
        $url = config('federado.http.url');

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
        $project = config('federado.bechmark_project_id');
        
        $connection = DB::connection();
        $prefix = !empty($connection->getTablePrefix()) ? '' : 'tb_';
        
        if (is_null($project)) {
            return DB::table($prefix . 'users')
                ->join($prefix . 'secret_user', $prefix . 'users.id', '=', $prefix . 'secret_user.user_id')
                ->where($prefix . 'secret_user.secret', '=', $user_secret)
                ->first();
        }

        return DB::table($prefix . 'users')
            ->join($prefix . 'secret_user', $prefix . 'users.id', '=', $prefix . 'secret_user.user_id')
            ->where('project', $project)
            ->where($prefix . 'secret_user.secret', '=', $user_secret)
            ->first();
    }
}