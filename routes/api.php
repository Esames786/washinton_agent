<?php
date_default_timezone_set('America/New_York');

use Illuminate\Http\Request;
use App\Http\Controllers\InstantQuoteApiController;

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

Route::post('/v2/website-quote','phone_quote\NewQuote@websiteShipa1Quote')->middleware('throttle:30,1');
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
 
