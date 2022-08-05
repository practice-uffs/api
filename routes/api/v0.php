<?php

use App\Http\Controllers\API\V0\AlunoController;
use App\Http\Controllers\API\V0\ApiProxyController;
use App\Http\Controllers\API\V0\AuraController;
use App\Http\Controllers\API\V0\ChannelsController;
use App\Http\Controllers\API\V0\NotificationController;
use App\Http\Controllers\API\V0\AuthController;
use App\Http\Controllers\API\V0\CheckinController;
use App\Http\Controllers\API\V0\EnvironmentController;
use App\Http\Controllers\API\V0\InteractionController;
use App\Http\Controllers\API\V0\PingController;
use App\Http\Controllers\API\V0\TestController;
use App\Http\Controllers\API\V0\UserController;
use App\Http\Controllers\API\V0\AnalyticsController;
use App\Http\Controllers\API\V0\WellBeingQuestionnaireController;
use App\Http\Proxy\PracticeApiProxy;
use App\Http\Livewire\AuraWidget;
use App\Http\Livewire\ShowAnalytics;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
| Api routes shown below available at the /v0/ url.
*/

Route::post('/auth', [AuthController::class, 'index'])->name('auth');
Route::get('/checkin/marker', [CheckinController::class, 'marker']);

// Aura Widget
Route::get('/widgets/aura', AuraWidget::class);

// Show Analytics 
Route::get('/analytics/show', ShowAnalytics::class);

// Authendicated routes
Route::group(['middleware' => 'jwt.practice'], function () {

    // Aura
    Route::get('/aura/nlp/{route}/{text}', [AuraController::class, 'index']);
    Route::get('/aura/nlp/{text}', [AuraController::class, 'mix']);
    Route::match(['GET', 'POST'], 'interact', [InteractionController::class, 'index']);

    // Channels
    Route::post('user/channels', [ChannelsController::class, 'store']);
    Route::patch('user/channels', [ChannelsController::class, 'update']);
    Route::delete('user/channels', [ChannelsController::class, 'destroy']);

    // Analytics

    Route::get('/analytics', [AnalyticsController::class, 'index']);
    Route::post('/analytics', [AnalyticsController::class, 'store']);
    Route::get('/analytics/{id}', [AnalyticsController::class, 'show']);
    Route::patch('/analytics/{id}', [AnalyticsController::class, 'update']);
    Route::delete('/analytics/{id}', [AnalyticsController::class, 'destroy']);

    // Questionário App Bem Estar
    Route::get('/app-bem-estar/questionnaire', [WellBeingQuestionnaireController::class, 'index']);
    Route::post('/app-bem-estar/questionnaire', [WellBeingQuestionnaireController::class, 'store']);
    Route::get('/app-bem-estar/questionnaire/{id}', [WellBeingQuestionnaireController::class, 'show']);
    Route::patch('/app-bem-estar/questionnaire/{id}', [WellBeingQuestionnaireController::class, 'update']);
    Route::delete('/app-bem-estar/questionnaire/{id}', [WellBeingQuestionnaireController::class, 'destroy']);

    // Notification
    Route::get('user/notify/push', [NotificationController::class, 'push']);

    // Environment
    Route::get('env', [EnvironmentController::class, 'index']);        

    // Check-in
    Route::post('/checkin', [CheckinController::class, 'store']);

    // Informações acadêmicas
    Route::get('/aluno/historico', [AlunoController::class, 'historico']);    

    

    // Proxy para apis de outros serviços
    // Mural
    PracticeApiProxy::resource('/{app}/orders');
    PracticeApiProxy::resource('/{app}/ideas');
    PracticeApiProxy::resource('/{app}/categories');
    PracticeApiProxy::resource('/{app}/services');
    PracticeApiProxy::resource('/{app}/locations');
    PracticeApiProxy::resource('/{app}/comments');
    Route::get('/{app}/me', [ApiProxyController::class, 'proxy']);

    // User
    Route::get('/user', [UserController::class, 'index']);
   

    // Test
    Route::get('ping', [PingController::class, 'index']);
});

// Test routes
if (env('APP_ENV') === 'local') {
    Route::group(['prefix' => '/test'], function () {
        Route::get('/passport', [TestController::class, 'passport'])->name('test.passport');    
        Route::get('/credentials', [TestController::class, 'credentials'])->name('test.credentials');    
    });
}
