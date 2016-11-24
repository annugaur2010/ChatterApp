<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

use Illuminate\Support\Facades\App;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class TestEvent implements ShouldBroadcast
{
    public $text;

    public function __construct($text)
    {
        $this->text = $text;
    }

    public function broadcastOn()
    {
        return ['test-channel'];
    }
}

Route::get('/', function () {
    return view('welcome');
});

// Notifcation Routes
Route::get('/notifications','NotificationController@getIndex');
Route::post('/notifications/notify','NotificationController@postNotify');

// Activity Events Routes
Route::get('/activities','ActivityController@getIndex');
Route::post('/activities/status-update', 'ActivityController@postStatusUpdate');
Route::post('/activities/like/{id}', 'ActivityController@postLike');

// Chat Routes
Route::get('/chat','ChatController@getIndex');
Route::post('/chat/message', 'ChatController@postMessage');

// Socialite Login Routes
Route::get('auth/github', 'Auth\AuthController@redirectToProvider');
Route::get('auth/github/callback', 'Auth\AuthController@handleProviderCallback');

Route::get('/bridge', function() {
    $pusher = App::make('pusher');

    $pusher->trigger( 'test-channel',
                      'test-event', 
                      array('text' => 'Preparing the Pusher Laracon.eu workshop!'));

    return view('welcome');
});

Route::get('/broadcast', function() {
    event(new TestEvent('Broadcasting in Laravel using Pusher!'));

    return view('welcome');
});