<?php
date_default_timezone_set('America/New_York');

use Illuminate\Http\Request;
use App\Http\Controllers\InstantQuoteApiController;
use App\Http\Controllers\Api\CrApplicationApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Public: default contract/T&C (used by CrazyRays signup form)
Route::get('/default-contract', 'EmployeeReviewController@publicDefaultContract')->middleware('throttle:60,1');

// CrazyRays campaign application submission (public, browser-direct, CORS enabled)
Route::options('/cr-application', function () { return response('', 200); })->middleware('crazyrays.cors');
Route::post('/cr-application', [CrApplicationApiController::class, 'store'])->middleware(['crazyrays.cors', 'throttle:20,1']);

// CrazyRays contact/career enquiry form (proxied from crazyrays server)
Route::options('/cr-contact', function () { return response('', 200); })->middleware('crazyrays.cors');
Route::post('/cr-contact', [CrApplicationApiController::class, 'contactNotify'])->middleware(['crazyrays.cors', 'throttle:10,1']);

// Bridge auth endpoints mirrored to /api/ path — avoids WAF/ModSecurity rules
// that block POST to URLs containing "login" on shared cPanel hosting.
// crazyrays uses DAYDISPATCH_LOGIN_ENDPOINT=/api/bridge/login
Route::post('/bridge/register',   'Bridge\BridgeAuthController@register')->middleware('throttle:20,1');
Route::post('/bridge/login',      'Bridge\BridgeAuthController@login')->middleware('throttle:20,1');
Route::post('/bridge/verify-otp', 'Bridge\BridgeAuthController@verifyOtp')->middleware('throttle:10,1');

Route::post('/v2/website-quote','phone_quote\NewQuote@websiteShipa1Quote')->middleware('throttle:30,1');
Route::post('/submit/instant-quote','phone_quote\AutohaulQuoteController@store')->middleware('throttle:30,1');
Route::post('/v2/submit_query','phone_quote\NewQuote@websiteQuery')->middleware('throttle:30,1');
Route::post('/v2/website-quote-auction','phone_quote\NewQuote@websiteShipa1QuoteAuction')->middleware('throttle:30,1');
Route::get('/get-card','phone_quote\customer\CustomerController@getCard');
Route::post('/tracking-order','phone_quote\NewQuote@trackingOrder')->middleware('throttle:60,1');
Route::get('/testingapi','phone_quote\NewQuote@testingapi');

// Instant quote from daydispatch
Route::post('/submit-instant-quote-DD', 'InstantQuoteApiController@submitInstantQuoteDD');

// Instant quote from daydispatch
Route::post('/submit-instant-quote', 'InstantQuoteApiController@submitInstantQuote');

// get order-email form
Route::get('/email_order_api/{id}/{email}','phone_quote\NewQuote@email_order_api');

// submit order-email form
Route::post('/email_order_api/submit','phone_quote\NewQuote@email_order_apiStore');

// submit order-email form card
Route::post('/email_orderCard_api/submit','phone_quote\NewQuote@email_order_apiStoreCard');
 


// ── RingCentral R-Dialer API ─────────────────────────────────────────────────
Route::get('/r', fn() => response()->json(['ok' => true]))->name('ringcentral.api.base');
Route::middleware(['auth:sanctum'])->prefix('r')->group(function () {
    Route::post('/send-sms',                                                          'RingCentralApiController@sendSMS')                   ->name('ringcentral.api.send-sms');
    Route::get('/calls',                                                              'RingCentralApiController@getCallHistory')             ->name('ringcentral.api.calls');
    Route::get('/calls/summary',                                                      'RingCentralApiController@getCallSummary')             ->name('ringcentral.api.calls.summary');
    Route::post('/calls/mark-seen',                                                   'RingCentralApiController@markCallsSeen')              ->name('ringcentral.api.calls.mark-seen');
    Route::get('/messages',                                                           'RingCentralApiController@getMessageHistory')          ->name('ringcentral.api.messages');
    Route::post('/messages/mark-read',                                                'RingCentralApiController@markMessagesRead')           ->name('ringcentral.api.messages.mark-read');
    Route::get('/voicemails',                                                         'RingCentralApiController@getVoicemails')              ->name('ringcentral.api.voicemails');
    Route::get('/voicemail/{id}',                                                     'RingCentralApiController@getVoicemail')               ->name('ringcentral.api.voicemail');
    Route::post('/voicemails/mark-status',                                            'RingCentralApiController@markVoicemailStatus')        ->name('ringcentral.api.voicemails.mark-status');
    Route::delete('/voicemail/{id}',                                                  'RingCentralApiController@deleteVoicemail')            ->name('ringcentral.api.voicemail.delete');
    Route::get('/call-control/sessions',                                              'RingCentralApiController@getTelephonySessions')       ->name('ringcentral.api.call-control.sessions');
    Route::post('/call-control/sessions/{sessionId}/parties/{partyId}/transfer',     'RingCentralApiController@transferTelephonyParty')     ->name('ringcentral.api.call-control.transfer');
    Route::delete('/call-control/sessions/{sessionId}/parties/{partyId}',            'RingCentralApiController@removeTelephonyParty')       ->name('ringcentral.api.call-control.remove-party');
    Route::post('/call-control/merge',                                                'RingCentralApiController@mergeTelephonySessions')     ->name('ringcentral.api.call-control.merge');
    Route::post('/telephony/conference',                                              'RingCentralApiController@createTelephonyConference')  ->name('ringcentral.api.conference');
    Route::post('/telephony/sessions/{sessionId}/parties/bring-in',                  'RingCentralApiController@bringInParty')               ->name('ringcentral.api.bring-in');
    Route::post('/call-control/sessions/{sessionId}/parties/{partyId}/switch-to-web','RingCentralApiController@switchToWebPhone')           ->name('ringcentral.api.switch-to-web');
    Route::get('/blocked-numbers',                                                    'RingCentralApiController@getBlockedNumbers')          ->name('ringcentral.api.blocked-numbers.list');
    Route::post('/blocked-numbers',                                                   'RingCentralApiController@addBlockedNumber')           ->name('ringcentral.api.blocked-numbers.add');
    Route::delete('/blocked-numbers/{id}',                                            'RingCentralApiController@removeBlockedNumber')        ->name('ringcentral.api.blocked-numbers.remove');
    Route::post('/blocked-numbers/check',                                             'RingCentralApiController@checkBlockedNumber')         ->name('ringcentral.api.blocked-numbers.check');
    Route::get('/blocked-numbers/settings',                                           'RingCentralApiController@getBlockedNumbersSettings')  ->name('ringcentral.api.blocked-numbers.settings.get');
    Route::patch('/blocked-numbers/settings',                                         'RingCentralApiController@updateBlockedNumbersSettings')->name('ringcentral.api.blocked-numbers.settings.update');
    Route::get('/blocked-numbers/debug',                                              'RingCentralApiController@debugBlockedNumbers')        ->name('ringcentral.api.blocked-numbers.debug');
    Route::get('/templates',                                                          'RingCentralApiController@getTemplates')               ->name('ringcentral.api.templates');
    Route::post('/templates',                                                         'RingCentralApiController@createTemplate')             ->name('ringcentral.api.templates.create');
    Route::get('/attachment',                                                         'RingCentralApiController@serveAttachment')            ->name('ringcentral.api.attachment');
    Route::get('/recording/{id}',                                                     'RingCentralApiController@getRecording')               ->name('ringcentral.api.recording');
    Route::get('/recordings',                                                         'RingCentralApiController@getRecordings')              ->name('ringcentral.refreshRecordings');
    Route::get('/phone-numbers',                                                      'RingCentralApiController@getPhoneNumbers')            ->name('ringcentral.api.phone-numbers');
    Route::get('/webphone-token',                                                     'RingCentralApiController@getWebPhoneToken')           ->name('ringcentral.api.webphone-token');
    Route::get('/webphone-token-timer',                                               'RingCentralApiController@getWebPhoneTokenTimer')      ->name('ringcentral.api.webphone-token-timer');
    Route::post('/close-instance',                                                    'RingCentralApiController@closeWebPhoneInstance')      ->name('ringcentral.api.close-instance');
    Route::get('/events/stream',                                                      'RingCentralApiController@streamWebhookEvents')        ->name('ringcentral.api.events.stream');
});
