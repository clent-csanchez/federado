<?php

return [

    /**
     * Datos de autenticacion
     */
    'secret' => env('APP_SECRET'),
    'sso_secret' => env('SSO_SECRET'),

    /**
     * Datos de aplicacion
     */
    'sso_plataform_id' => env('SSO_PLATAFORM_ID'),


    /**
     * peticion http
     */
    'http' => [
        'url' => 'http://testing.clent.cl:8058/api/user',
    ],

    /**
     * declarar ruta de redireccion
     */
    'redirect' => '/home'
];  