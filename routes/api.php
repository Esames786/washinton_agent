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
Route::middleware(['auth:sanctum'])->prefix('r')->group(function () {
    Route::post('/send-sms',                                                     'RingCentralApiController@sendSMS');
    Route::get('/calls',                                                         'RingCentralApiController@getCallHistory');
    Route::get('/calls/summary',                                                 'RingCentralApiController@getCallSummary');
    Route::post('/calls/mark-seen',                                              'RingCentralApiController@markCallsSeen');
    Route::get('/messages',                                                      'RingCentralApiController@getMessageHistory');
    Route::post('/messages/mark-read',                                           'RingCentralApiController@markMessagesRead');
    Route::get('/voicemails',                                                    'RingCentralApiController@getVoicemails');
    Route::get('/voicemail/{id}',                                                'RingCentralApiController@getVoicemail');
    Route::post('/voicemails/mark-status',                                       'RingCentralApiController@markVoicemailStatus');
    Route::delete('/voicemail/{id}',                                             'RingCentralApiController@deleteVoicemail');
    Route::get('/call-control/sessions',                                         'RingCentralApiController@getTelephonySessions');
    Route::post('/call-control/sessions/{sessionId}/parties/{partyId}/transfer', 'RingCentralApiController@transferTelephonyParty');
    Route::delete('/call-control/sessions/{sessionId}/parties/{partyId}',        'RingCentralApiController@removeTelephonyParty');
    Route::post('/call-control/merge',                                           'RingCentralApiController@mergeTelephonySessions');
    Route::post('/telephony/conference',                                         'RingCentralApiController@createTelephonyConference');
    Route::post('/telephony/sessions/{sessionId}/parties/bring-in',             'RingCentralApiController@bringInParty');
    Route::post('/call-control/sessions/{sessionId}/parties/{partyId}/switch-to-web', 'RingCentralApiController@switchToWebPhone');
    Route::get('/blocked-numbers',                                               'RingCentralApiController@getBlockedNumbers');
    Route::post('/blocked-numbers',                                              'RingCentralApiController@addBlockedNumber');
    Route::delete('/blocked-numbers/{id}',                                       'RingCentralApiController@removeBlockedNumber');
    Route::post('/blocked-numbers/check',                                        'RingCentralApiController@checkBlockedNumber');
    Route::get('/blocked-numbers/settings',                                      'RingCentralApiController@getBlockedNumbersSettings');
    Route::patch('/blocked-numbers/settings',                                    'RingCentralApiController@updateBlockedNumbersSettings');
    Route::get('/blocked-numbers/debug',                                         'RingCentralApiController@debugBlockedNumbers');
    Route::get('/templates',                                                     'RingCentralApiController@getTemplates');
    Route::post('/templates',                                                    'RingCentralApiController@createTemplate');
    Route::get('/attachment',                                                    'RingCentralApiController@serveAttachment');
    Route::get('/recording/{id}',                                                'RingCentralApiController@getRecording');
    Route::get('/recordings',                                                    'RingCentralApiController@getRecordings');
    Route::get('/phone-numbers',                                                 'RingCentralApiController@getPhoneNumbers');
    Route::get('/webphone-token',                                                'RingCentralApiController@getWebPhoneToken');
    Route::get('/webphone-token-timer',                                          'RingCentralApiController@getWebPhoneTokenTimer');
    Route::post('/close-instance',                                               'RingCentralApiController@closeWebPhoneInstance');
    Route::get('/events/stream',                                                 'RingCentralApiController@streamWebhookEvents');
});
