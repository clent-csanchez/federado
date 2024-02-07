<?php

use Clent\Federado\Http\Controllers\RemoteAccessController;
use Illuminate\Support\Facades\Route;

Route::group([
    'as'        => 'web.remote.',
    'middleware'=> ['validate.incoming.session.token','web'],
],function(){
    Route::get('callback',RemoteAccessController::class)->name('auth.access');
});