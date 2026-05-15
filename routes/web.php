<?php

use App\Http\Controllers\OrderPricingController;
use App\Http\Controllers\phone_quote\neworder\NewOrder;
use App\Http\Controllers\SiteSettingsController;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\SendCustomEmail;
use App\Http\Controllers\AuthorizationFormController;
use App\Http\Controllers\PriceRangeController;
use App\Http\Controllers\LogoutQuestionController;
use App\Http\Controllers\LogoutQuestionsAnswerController;
use App\Http\Controllers\CpanelEmailController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

date_default_timezone_set('America/New_York');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//Route::get('/', function () {
//    return view('welcome');
//});
Route::get('/idle', function () {
    return view('idle');
})->name('idle.page');

Route::get('/file_route', 'WelcomeController@file_route');
Route::get('/update_mouse', 'WelcomeController@updateMouse');
Route::get('/start_time', 'WelcomeController@start_time');
Route::get('/end_time', 'WelcomeController@end_time');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');

Route::get('/storage-link', function () {
    $output = Artisan::call('storage:link');
    return nl2br(Artisan::output());
});


// Route::get('profile', function () {

// })->middleware('auth');

Route::get('/clear_cache', function () {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return back()->with('msg', 'Cache clear successfully!');
});

Route::get('/version-info', function () {
    return response()->json([
        'laravel_version' => app()->version(),
        'php_version' => PHP_VERSION,
    ]);
});
//welcome

// Public frontend home — shows the DayDispatch website
Route::get('/', 'FrontendController@home')->name('Frontend.index');

// Public frontend pages (no auth required)
Route::get('/about_us', 'FrontendController@aboutUs')->name('Frontend.about.us');
Route::get('/faq', 'FrontendController@faq')->name('Frontend.faq');
Route::get('/terms', 'FrontendController@terms')->name('Frontend.terms');
Route::get('/contact_us', 'FrontendController@contactUs')->name('Frontend.contact.us');
Route::get('/Quote-Request', 'FrontendController@quoteRequest')->name('Frontend.qoute.request');
Route::post('/Quote-Request', 'FrontendController@submitQuoteRequest')->name('Frontend.qoute.submit');
Route::post('/Post-Instant-Quote', 'FrontendController@submitQuoteRequest')->name('Post.Instant.Quote');
Route::post('/Submit-Contact-Lead', 'FrontendController@submitContactLead')->name('Post.Contact.Lead');
Route::get('/autocomplete', 'FrontendController@autocomplete')->name('autocomplete');
Route::get('/Quote-Request/confirmation', 'FrontendController@quoteConfirmation')->name('Frontend.qoute.confirmation');
Route::get('/Is-It-For-Me', 'FrontendController@isItForMe')->name('Frontend.is.me');
// Additional frontend routes referenced in the layout
Route::get('/Carriers', 'FrontendController@carriers')->name('Frontend.Carrier');
Route::get('/Brokers', 'FrontendController@brokers')->name('Frontend.Broker');
Route::get('/Shippers', 'FrontendController@shippers')->name('Frontend.Shipper');
Route::get('/Dispatch', 'FrontendController@dispatchPage')->name('Frontend.Dispatch');
Route::get('/Testimonials', 'FrontendController@testimonials')->name('Frontend.Testimonials');
Route::get('/Load-Board', 'FrontendController@loadboard')->name('Frontend.loadboard');
Route::get('/LoadBoard-Packages', 'FrontendController@packages')->name('Frontend.Packages');
Route::get('/Privacy-Policy', 'FrontendController@privacy')->name('Frontend.privacy');

// Keep login accessible at /loginn
Route::get('/loginn', 'WelcomeController@loginn');
Route::get('/login_mohsin', 'WelcomeController@login_mohsin');
Route::post('/getlogin2', 'WelcomeController@getlogin2')->name('getlogin2');
Route::get('verify/{id}/{id2}/{id3}', 'WelcomeController@getVerification');
Route::post('/code_verify', 'WelcomeController@codeVerify')->name('code_verify');
Route::post('/resend_verify', 'WelcomeController@resend_verify')->name('resend_verify');

// Public self-service signup (no auth required)
Route::get('/register', 'PublicSignupController@showForm')->name('register');
Route::post('/register', 'PublicSignupController@store')->name('register.store');

// DASHBOARD
Route::get('/dashboard', 'DashboardController@getDashboard')->name('dashboard');
Route::get('/logout', 'WelcomeController@logout');
Route::get('/logoutAllAccounts', 'WelcomeController@logoutAllAccounts');


Route::get('/getmake', 'phone_quote\NewQuote@getmake');
Route::get('/getmodel', 'phone_quote\NewQuote@getmodel');
Route::get('/get_zip', 'phone_quote\NewQuote@get_zip');
Route::get('/link-clicked', 'WebsiteLinkController@linkClicked');
Route::get('/website-order/{id}/{userid}', 'phone_quote\NewQuote@website_order')->name('website-order');
Route::get('/email_order/{id}/{userid}', 'phone_quote\NewQuote@email_order')->name('email_order');
Route::get('/email_order/{id}/{userid}/{token}', 'phone_quote\NewQuote@email_order')->name('email_order.token');
Route::get('/demand_order/{id}/{userid}', 'DemandController@demand_order')->name('demand_order');
Route::post('/order_payment', 'phone_quote\NewQuote@order_payment')->name('order_payment');
Route::post('/order_payment_card', 'phone_quote\NewQuote@order_payment_card')->name('order_payment_card');
Route::get('/order_payment_card_us/{id}', 'phone_quote\NewQuote@order_payment_card_us')->name('order_payment_card_us');

Route::get('/order-payment-success', function () {
    return view('main.phone_quote.new_quote.emailordersuccess');
})->name('order.payment.success');

Route::get('/order_tracking/{id}', [App\Http\Controllers\phone_quote\NewQuote::class, 'order_tracking'])
    ->name('order.tracking');
// flag users
Route::get('/flag-users', 'phone_quote\NewQuote@flagUsers')->name('flagUsers');
Route::get('/flag-chat2', 'phone_quote\NewQuote@flagUsers2');

// new chat beep
Route::get('/checkNewChat', 'phone_quote\NewQuote@checkNewChat');

// Authorization Form
Route::get('/authorization/form', 'AuthorizationFormController@index')->name('authorization.form');

// Authorization Form Submit
Route::post('/authorization/form/submit', 'AuthorizationFormController@store')->name('authorization.form.submit');

// Authorization Form Submit
Route::post('/authorization/form/email', 'AuthorizationFormController@email')->name('authorization.form.email');

Route::group(['middleware' => ['auth']], function () {

    Route::post('/auto_screenshot', 'phone_quote\customer\CustomerController@auto_screenshot');
    Route::get('/time_user', 'phone_quote\customer\CustomerController@time_user');
    Route::get('/edit_employee/{id}', 'DashboardController@edit_employee');
    Route::post('/update_employee', 'DashboardController@update_employee')->name('update_employee');
    Route::get('/hr-portal/{userId}', 'HrPortalRedirectController@redirect')->name('hr.portal.redirect');
    Route::get('/hr-portal/admin/employee/{userId}', 'HrPortalRedirectController@adminRedirect')->name('hr.admin.employee');

    // Employee Review Modal
    Route::get('/employee-review/data/{userId}', 'EmployeeReviewController@data')->name('employee.review.data');
    Route::post('/employee-review/agent-status', 'EmployeeReviewController@changeAgentStatus')->name('employee.review.agent_status');
    Route::post('/employee-review/hr-status', 'EmployeeReviewController@changeHrStatus')->name('employee.review.hr_status');
    Route::post('/employee-review/verify-document', 'EmployeeReviewController@verifyDocument')->name('employee.review.verify_document');
    Route::post('/employee-review/bulk-verify-documents', 'EmployeeReviewController@bulkVerifyDocuments')->name('employee.review.bulk_verify_documents');
    Route::post('/employee-review/save-contract', 'EmployeeReviewController@saveContract')->name('employee.review.save_contract');
    Route::post('/employee-review/accept-contract', 'EmployeeReviewController@acceptContract')->name('employee.review.accept_contract');

    // Scope (account impersonation — admin only)
    Route::get('/scope/enter/{userId}', 'ScopeController@enter')->name('scope.enter');
    Route::get('/scope/exit', 'ScopeController@exit')->name('scope.exit');

    // Commission screen
    Route::get('/commission', 'CommissionController@index')->name('commission.index');
    Route::get('/commission/{userId}', 'CommissionController@show')->name('commission.show');
    Route::get('/user_active_new/{id}', 'DashboardController@user_active');
    Route::get('/user_deactive_new/{id}', 'DashboardController@user_deactive');
    Route::post('/freeze-unfreeze-new/{id}', 'DashboardController@freezeUnfreeze');
    Route::get('/reset/{id}', 'DashboardController@reset');
    Route::get('/delete_employee/{id}', 'DashboardController@delete_employee');
    Route::get('/screen_shots/{id}', 'DashboardController@screen_shots');
    Route::post('/role-access', 'DashboardController@roleAccess');

    // ── cPanel Email Management (permission 162) ──────────────────────────
    Route::get('/cpanel-email', 'MailboxAccountController@index')->name('cpanel.email.index');
    Route::post('/cpanel-email', 'MailboxAccountController@store')->name('cpanel.email.store');
    Route::post('/cpanel-email/{emailAccount}/update', 'MailboxAccountController@update')->name('cpanel.email.update');
    Route::post('/cpanel-email/{emailAccount}/reset-password', 'MailboxAccountController@resetPassword')->name('cpanel.email.reset_password');
    Route::post('/cpanel-email/{emailAccount}/toggle', 'MailboxAccountController@toggleStatus')->name('cpanel.email.toggle');
    Route::post('/cpanel-email/{emailAccount}/delete', 'MailboxAccountController@destroy')->name('cpanel.email.destroy');

    // ── Mailbox (permission 163) ──────────────────────────────────────────
    Route::get('/mailbox', 'MailboxController@index')->name('mailbox.index');
    Route::get('/mailbox/folders', 'MailboxController@folders')->name('mailbox.folders');
    Route::get('/mailbox/folder/{folder}', 'MailboxController@folder')->name('mailbox.folder');
    Route::get('/mailbox/message/{folder}/{uid}', 'MailboxController@message')->name('mailbox.message');
    Route::get('/mailbox/attachment/{folder}/{uid}/{attachmentIndex}', 'MailboxController@downloadAttachment')->name('mailbox.attachment');
    Route::post('/mailbox/send', 'MailboxSendController@send')->name('mailbox.send');
    Route::post('/mailbox/inline-upload', 'MailInlineUploadController@store')->name('mailbox.inline_upload');

    // Instant quote from daydispatch
    Route::post('/submit-instant-quote', [InstantQuoteController::class, 'submitInstantQuote'])->name('instantQuote.store');

    // Display a listing of the resource.
    Route::get('/field_labels', [SiteSettingsController::class, 'index'])->name('field_labels.index');

    // Show the form for creating a new resource.
    Route::get('/field_labels/create', [SiteSettingsController::class, 'create'])->name('field_labels.create');

    // Store a newly created resource in storage.
    Route::post('/field_labels', [SiteSettingsController::class, 'store'])->name('field_labels.store');

    // Display the specified resource.
    Route::get('/field_labels/{id}', [SiteSettingsController::class, 'show'])->name('field_labels.show');

    // Show the form for editing the specified resource.
    Route::get('/field_labels/{id}/edit', [SiteSettingsController::class, 'edit'])->name('field_labels.edit');

    // Update the specified resource in storage.
    Route::post('/field_labels/update', [SiteSettingsController::class, 'update'])->name('field_labels.update');

    // Remove the specified resource from storage.
    Route::delete('/field_labels/{id}', [SiteSettingsController::class, 'destroy'])->name('field_labels.destroy');

    // Remove the specified resource from storage.
    Route::get('/field_labels/history/{field_name}', [SiteSettingsController::class, 'LabeleHistory'])->name('field_labels.history');

    // Remove the specified resource from storage.
    Route::post('/order/updateNature', 'phone_quote\NewQuote@updateNature')->name('updateNature');

    // Get Single CustomerNature Values
    Route::get('/get/CustomerNature', 'phone_quote\NewQuote@getCustomerNature')->name('getCustomerNature');

    // Get Single CustomerNature Values
    Route::get('/customerNature/list', 'phone_quote\NewQuote@customerNatureList')->name('customerNatureList');

    // Get Single CustomerNature Values
    Route::get('/customerNature/filter', 'phone_quote\NewQuote@filterCustomerNatureList')->name('filterCustomerNatureList');

    // Authorization Form Submit
    Route::get('/authorization-forms', 'AuthorizationFormController@allForms')->name('authorization.forms.index');

    // Authorization Form Submit
    Route::get('/authorization-form/show/{id}', 'AuthorizationFormController@show')->name('authorization.forms.show');

    // Authorization Form Submit
    Route::resource('messagechats', 'MessageChatController');
    Route::get('/get/order/ids/{id}', 'MessageChatController@getOrderId')->name('get.order.ids');
    Route::get('/get/messageChatResults/result', 'MessageChatController@filterMessageChat')->name('fetch.messagechat.results');
    Route::get('/get/messageChats', 'MessageChatController@getAllMsgChats')->name('get.all.messagechat');

    // price-range
    Route::get('/price-range', 'PriceRangeController@index')->name('get.all.priceRange');
    Route::get('/price-range/create', 'PriceRangeController@create')->name('create.priceRange');
    Route::post('/price-range/store', 'PriceRangeController@store')->name('store.priceRange');
    Route::get('/price-range/edit/{id}', 'PriceRangeController@edit')->name('edit.priceRange');
    Route::post('/price-range/update/{id}', 'PriceRangeController@update')->name('update.priceRange');
    Route::get('/price-range/destroy/{id}', 'PriceRangeController@destroy')->name('destroy.priceRange');

    // edit send price mail
    Route::get('/send/price/mail/{id}', 'phone_quote\NewQuote@priceChangeMail')->name('send.price.mail');

    // assign new price
    Route::post('/give/price', 'PriceRangeController@givenPrice')->name('price_giver.give.price');

    // Authorization Form Download
    Route::get('/download-pdf', [PDFController::class, 'downloadPDF'])->name('authorization.forms.download');

    // logout questions crud
    Route::get('/logout_questions', [LogoutQuestionController::class, 'index'])->name('logout_questions.index');
    Route::get('/logout_questions/create', [LogoutQuestionController::class, 'create'])->name('logout_questions.create');
    Route::post('/logout_questions', [LogoutQuestionController::class, 'store'])->name('logout_questions.store');
    Route::get('/logout_questions/{id}/edit', [LogoutQuestionController::class, 'edit'])->name('logout_questions.edit');
    Route::put('/logout_questions/{id}', [LogoutQuestionController::class, 'update'])->name('logout_questions.update');
    Route::delete('/logout_questions/{id}', [LogoutQuestionController::class, 'destroy'])->name('logout_questions.destroy');
    Route::get('/get_user_name', [LogoutQuestionController::class, 'getUserName'])->name('get.user.name');

    Route::get('/revert_to_new/{id}', 'phone_quote\NewQuote@revert_to_new')->name('revert.to.new');

    //block phone
    Route::get('/block_phones', 'phone_quote\NewQuote@block_phone')->name('block_phone.index');

    Route::post('/block_phone/store', 'phone_quote\NewQuote@block_phone_submit')->name('block_phone.store');

    Route::post('/block_phone/update_status', 'phone_quote\NewQuote@updateStatus')->name('block_phone.updateStatus');

    Route::get('/request_price', 'phone_quote\NewQuote@requestPrice')->name('requestPrice.index');

    Route::get('/allow_price_giver/{id}', 'phone_quote\NewQuote@allow_price_giver')->name('allow.price.giver');

    Route::get('/check_Quote_Exists', 'phone_quote\NewQuote@checkQuoteExists')->name('check.quote.exists');

    Route::get('/change/car_type', 'phone_quote\NewQuote@changeCarType')->name('change.car_type');

    Route::get('/get-old-price', 'phone_quote\NewQuote@getOldPrice')->name('get.old.price');

    Route::get('/get_email_history', 'phone_quote\neworder\NewOrder@getEmailHistory')->name('get.email.history');

    Route::get('/view_employee_revenue', 'DashboardController@view_employee_revenue')->name('view_employee_revenue');
    Route::get('/view_employee_revenue_deliveryBoy', 'DashboardController@view_employee_revenue_deliveryBoy')->name('view_employee_revenue_deliveryBoy');
    Route::get('/view_employee_revenue_Dispatcher', 'DashboardController@view_employee_revenue_Dispatcher')->name('view_employee_revenue_Dispatcher');
    Route::get('/view_employee_revenue_PrivateOT', 'DashboardController@view_employee_revenue_PrivateOT')->name('view_employee_revenue_PrivateOT');
    Route::get('/get_employee', 'DashboardController@getEmployee')->name('get_employee');
    Route::post('/update_employee_revenue', 'DashboardController@update_employee_revenue')->name('update_employee_revenue');
    Route::get('/search_allNew_user', 'DashboardController@searchAllNewUser')->name('search.allNew.user');

    Route::get('/view_query', 'DashboardController@view_query')->name('view_query');
    Route::get('/shipa1_query/whatsapp_count_dealer', 'DashboardController@shipa1_queryPhoneCount_dealer')->name('shipa1_query.phone.count_dealer');

    Route::get('/shipa1_query/history_dealer', 'DashboardController@shipa1_querygetHistory')->name('shipa1_query.call.history');
    Route::post('/shipa1_query/history_store_dealer', 'DashboardController@shipa1_querygetstoreHistory')->name('shipa1_query.store.call.history');
    Route::post('/shipa1_query/store_dealer', 'DashboardController@shipa1_queryNew')->name('store.autos.shipa1_query');

    Route::get('/shipa1_query_reporting', 'DashboardController@shipa1_query_reporting')->name('shipa1_query.reporting');
    Route::get('/shipa1_query/filter', 'DashboardController@filterAssignedQuery')->name('filter.shipa1_query');







    // logout question answers crud
    Route::get('/logout_questions_answers', [LogoutQuestionsAnswerController::class, 'index'])->name('logout_questions_answers.index');
    Route::get('/logout_questions_answers/create', [LogoutQuestionsAnswerController::class, 'create'])->name('logout_questions_answers.create');
    Route::post('/logout_questions_answers', [LogoutQuestionsAnswerController::class, 'store'])->name('logout_questions_answers.store');
    Route::get('/get-user-answer', [LogoutQuestionsAnswerController::class, 'getUserAns'])->name('logout_questions_answers.getUserAns');
    Route::post('/qa-comment/store', [LogoutQuestionsAnswerController::class, 'storeQComment'])->name('logoutQComment.store');
    Route::post('/logout_questions_answers/filter', [LogoutQuestionsAnswerController::class, 'filter'])->name('logoutQComment.filter');

    Route::group(['middleware' => ['all-roles']], function () {
        //Employee
        Route::get('/last_activity', 'WelcomeController@last_activity');
        Route::get('/freeze_user', 'WelcomeController@freeze_user');
        Route::get('/get-users-by-role', 'WelcomeController@getUsersByRole');
        Route::get('/break_time', 'WelcomeController@break_time');
        Route::get('/add_employee', 'DashboardController@add_employee');
        Route::post('/save_employee', 'DashboardController@save_employee')->name('save_employee');
        Route::get('/view_employee', 'DashboardController@view_employee');
        Route::post('/show_own_order', 'DashboardController@show_own_order');
        Route::post('/user-ss', 'DashboardController@user_ss');
        Route::get('/flag_employee', 'DashboardController@flag_employee');
        Route::post('/remove_employee_flag', 'DashboardController@removeFlag')->name('remove_employee_flag');
        Route::post('/add_employee_flag', 'DashboardController@redFlag')->name('add_employee_flag');
        Route::get('/recover_employee/{id}', 'DashboardController@recover_employee');
        Route::get('/update_password', 'DashboardController@update_password')->name('update_password');
        Route::post('/update_password_post', 'DashboardController@update_password_post')->name('update_password_post');
        Route::get('/other_pass', 'DashboardController@other_pass')->name('other_pass');
        Route::post('/other_pass_update', 'DashboardController@other_pass_update')->name('other_pass_update');

        //Quote
        Route::get('/add_new', 'phone_quote\NewQuote@add_new');
        Route::get('/add_new_heavy', 'phone_quote\NewQuote@add_new_heavy');
        Route::get('/add_new_freight', 'phone_quote\NewQuote@add_new_freight');
        Route::get('/get_order', 'phone_quote\NewQuote@get_order');
        Route::get('/get_old_data_phone_zip', 'phone_quote\NewQuote@get_old_data_phone_zip');
        Route::get('/get_canelation_data', 'phone_quote\NewQuote@get_canelation_data');
        Route::get('/get_last_5', 'phone_quote\NewQuote@get_last_5');
        Route::get('/get_last_5_2', 'phone_quote\NewQuote@get_last_5_2');
        Route::post('/createneworder', 'phone_quote\NewQuote@create_new_order')->name('createneworder');
        Route::get('/getPhoneCard', 'phone_quote\NewQuote@getPhoneCard')->name('getPhoneCard');
        Route::post('/store_new_quote', 'phone_quote\NewQuote@store_new_quote');
        Route::post('/auto_save_order', 'phone_quote\NewQuote@store_new_quote')->name('auto_save_order');
        Route::get('/listed_sheet/{id}', 'phone_quote\NewQuote@listed_sheet')->name('listed_sheet');
        Route::get('/dispatch_sheet/{id}', 'phone_quote\NewQuote@dispatch_sheet')->name('dispatch_sheet');
        Route::get('/pickedup_sheet/{id}', 'phone_quote\NewQuote@pickedup_sheet')->name('pickedup_sheet');
        Route::get('/auction_pickedup_sheet/{id}', 'phone_quote\NewQuote@auction_pickedup_sheet')->name('auction_pickedup_sheet');
        Route::get('/delivery_sheet/{id}', 'phone_quote\NewQuote@delivery_sheet')->name('delivery_sheet');
        Route::get('/completed_sheet/{id}', 'phone_quote\NewQuote@completed_sheet')->name('completed_sheet');
        Route::get('/sheet_data/{id}', 'phone_quote\NewQuote@sheet_data');
        Route::post('/sheet_data_save', 'phone_quote\NewQuote@sheet_data_save');
        Route::get('/sheet_list', 'phone_quote\NewQuote@sheet_list');
        Route::get('/previous-orders', 'phone_quote\NewQuote@previous_orders');
        Route::get('/previous-orders2', 'phone_quote\NewQuote@previous_orders2');
        Route::get('/rates_shipa1', 'phone_quote\NewQuote@rates_shipa1');
        Route::post('/create_sheet', 'phone_quote\NewQuote@create_sheet');
        Route::get('/get_sheet_data', 'phone_quote\NewQuote@get_sheet_data');
        Route::post('/change_order_price', 'phone_quote\NewQuote@change_order_price');
        Route::get('/new-auction-detail', 'phone_quote\NewQuote@newAuctionDetail');
        Route::get('/agents-report/view', 'phone_quote\NewQuote@agentReport')->name('agent.report');
        Route::post('/agents-report/save', 'phone_quote\NewQuote@saveAgentReport')->name('agent.report.save');

        //previous prices
        Route::get('/records_city_zip_destination', 'phone_quote\NewQuote@recordsCityZip')->name('records_city_zip_destination');

        //save Price
        Route::post('/savePrice', 'phone_quote\NewQuote@savePrice')->name('savePrice');
        Route::post('/upload_image', 'phone_quote\NewQuote@upload')->name('upload.image');
        Route::get('/request_check_price', 'phone_quote\NewQuote@request_check_price')->name('request.check.price');
        Route::get('/get_check_price', 'phone_quote\NewQuote@get_check_price')->name('get.check.price');
        Route::get('/fetch_check_price', 'DashboardController@fetchCheckPrice')->name('fetch.check.price');
        Route::get('/check_for_price', 'phone_quote\NewQuote@checkForPrice')->name('check.for.price');
        Route::get('/previous_check_prices', 'phone_quote\NewQuote@previousCheckPrices')->name('previous.check.prices');
        Route::get('/fetchNotifications', 'phone_quote\NewQuote@fetchNotifications')->name('fetch.notifications');


        Route::get('/price_requests/pending', 'phone_quote\NewQuote@price_request_pending')->name('price_requests.pending');
        Route::get('/price_requests/dispatch/{id}', 'phone_quote\NewQuote@price_request_dispatcher_view')->name('price_request.dispatcher');
        Route::get('/price_request_assign_dispatcher', 'phone_quote\neworder\NewOrder@price_request_assign_dispatcher')->name('price_request_assign_dispatcher');

        // Central Gateway portal iframes
        Route::get('/gateway-portal/hello', 'DashboardController@gatewayPortalHello')->name('gateway.portal.hello');
        Route::get('/gateway-portal/autohaul', 'DashboardController@gatewayPortalAutohaul')->name('gateway.portal.autohaul');

        Route::patch('/price-check/dispatchers/{user}/allow', 'phone_quote\neworder\NewOrder@togglePriceCheck')
            ->name('pricecheck.dispatchers.toggle');
        //request price
        Route::get('/get-request-order', 'phone_quote\RequestPrice\RequestPriceController@index');
        Route::post('/request', 'phone_quote\RequestPrice\RequestPriceController@create');
        Route::get('/get-request', 'phone_quote\RequestPrice\RequestPriceController@edit');
        Route::get('/get-request2', 'phone_quote\RequestPrice\RequestPriceController@edit2');
        Route::get('/update-price-request', 'phone_quote\RequestPrice\RequestPriceController@update');
        Route::get('/update-price-request2', 'phone_quote\RequestPrice\RequestPriceController@update2');
        Route::get('/get-req-price', 'phone_quote\RequestPrice\RequestPriceController@store');
        Route::get('/complete-request/{id}', 'phone_quote\RequestPrice\RequestPriceController@show');

        //faisal pasha
        Route::resource('payment_system', 'PaymentSystemController');
        Route::get('payment_system2', 'PaymentSystemController@payment_system2');
        Route::get('/getting_cards', 'PaymentSystemController@create');
        Route::get('/payment_log_amount', 'PaymentSystemController@payment_log_amount');
        Route::get('/reports', 'ReportsController@index');
        Route::get('/reports/get', 'ReportsController@show');
        Route::get('/reports/getNew', 'ReportsController@showNew')->name('agentReportNew');
        Route::get('/reports/get2', 'ReportsController@show2');
        Route::get('/reports/get2New', 'ReportsController@show2New');
        Route::get('/reports/search', 'ReportsController@search');
        Route::get('filter_payment', 'PaymentSystemController@filter_payment');


        Route::post('/send_order_link', 'phone_quote\NewQuote@send_order_link')->name('send_order_link');
        Route::get('/print_summary/{id}', 'phone_quote\NewQuote@print_summary')->name('print_summary');
        Route::get('/print_report/{id}', 'phone_quote\NewQuote@print_report')->name('print_report');
        Route::get('/old_shipa1_summary/{id}', 'phone_quote\NewQuote@old_shipa1_summary')->name('old_shipa1_summary');
        Route::get('/check_phone', 'phone_quote\NewQuote@check_phone');
        Route::get('/getvin', 'phone_quote\NewQuote@getvin');
        Route::get('/guides', 'phone_quote\management\ManagementController@guides');
        Route::get('/add_guide', 'phone_quote\management\ManagementController@add_guide');
        Route::get('/delete_guide/{id}', 'phone_quote\management\ManagementController@del_guide')->name('del_guide');
        Route::get('/add_guide_list', 'phone_quote\management\ManagementController@add_guide_list');
        Route::post('/add_guide_post', 'phone_quote\management\ManagementController@add_guide_post');
        Route::get('/guide/{any}', 'phone_quote\management\ManagementController@guide');
        Route::get('/tags', 'phone_quote\management\ManagementController@tags');
        Route::get('/luxury', 'phone_quote\management\ManagementController@luxuryVehicle');
        Route::get('/non-luxury', 'phone_quote\management\ManagementController@nonLuxuryVehicle');
        Route::get('/vehicle_body_type', 'phone_quote\management\ManagementController@vehicle_body_type');
        Route::get('/vehicle_parts', 'phone_quote\management\ManagementController@vehicle_parts');
        Route::get('/trailers', 'phone_quote\management\ManagementController@trailers');
        Route::get('/vehicle_condition', 'phone_quote\management\ManagementController@vehicle_condition');
        Route::get('/motorcycle_body_type', 'phone_quote\management\ManagementController@motorcycle_body_type');
        Route::get('/heavy_equipments', 'phone_quote\management\ManagementController@heavy_equipments');
        Route::get('/boat_shipping', 'phone_quote\management\ManagementController@boat_shipping');
        Route::get('/show_payment_logs', 'phone_quote\management\ManagementController@show_payment_logs');

        //price per mile
        Route::get('/mile-price', 'phone_quote\Mile\MileController@index');
        Route::post('/mile-price/store', 'phone_quote\Mile\MileController@store');
        Route::get('/mile-price/destroy/{id}', 'phone_quote\Mile\MileController@destroy');
        Route::get('/mile-price/edit', 'phone_quote\Mile\MileController@edit');
        Route::put('/mile-price/update/{id}', 'phone_quote\Mile\MileController@update');
        Route::get('/filtered-data', 'phone_quote\Mile\MileController@show');
        Route::post('/filtered-search', 'phone_quote\Mile\MileController@search');

        //offer price
        Route::get('/offer-price', 'phone_quote\Mile\MileController@indexOfferPrice');
        Route::post('/offer-price/store', 'phone_quote\Mile\MileController@storeOfferPrice');
        Route::get('/offer-price/destroy/{id}', 'phone_quote\Mile\MileController@destroyOfferPrice');
        Route::get('/offer-price/edit', 'phone_quote\Mile\MileController@editOfferPrice');
        Route::put('/offer-price/update', 'phone_quote\Mile\MileController@updateOfferPrice');
        Route::get('/offer-price/get_commission', 'phone_quote\Mile\MileController@get_commission');

        //commission range
        Route::get('/commission_range', 'phone_quote\Mile\MileController@index_commission_range');
        Route::post('/commission_range/store', 'phone_quote\Mile\MileController@store_commission_range');
        Route::get('/commission_range/destroy/{id}', 'phone_quote\Mile\MileController@destroy_commission_range');
        Route::get('/commission_range/edit', 'phone_quote\Mile\MileController@edit_commission_range');
        Route::put('/commission_range/update', 'phone_quote\Mile\MileController@update_commission_range');

        Route::get('/role', 'RoleController@index');
        Route::get('/role/create', 'RoleController@create');
        Route::post('/role/store', 'RoleController@store');
        Route::get('/role/edit/{id}', 'RoleController@edit');
        Route::post('/role/update/{id}', 'RoleController@update');
        Route::get('/role/destroy/{id}', 'RoleController@destroy');

        Route::get('/ip_address', 'phone_quote\Ip\IpController@index');
        Route::post('/ip_address/store', 'phone_quote\Ip\IpController@store');
        Route::get('/ip_address/edit', 'phone_quote\Ip\IpController@edit');
        Route::put('/ip_address/update/{id}', 'phone_quote\Ip\IpController@update');
        Route::get('/ip_address/destroy/{id}', 'phone_quote\Ip\IpController@destroy');
        //group chats
        Route::get('/group', 'phone_quote\Group\GroupController@index');
        Route::post('/group/store', 'phone_quote\Group\GroupController@store');
        Route::get('/group/destroy/{id}', 'phone_quote\Group\GroupController@destroy');
        Route::get('/group/active/{id}', 'phone_quote\Group\GroupController@active');
        Route::get('/group/notActive/{id}', 'phone_quote\Group\GroupController@notActive');
        Route::get('/group/edit/{id}', 'phone_quote\Group\GroupController@edit');
        Route::put('/group/update/{id}', 'phone_quote\Group\GroupController@update');

        Route::get('/get_group_chat', 'phone_quote\Group\GroupController@get_group_chat')->name('get_group_chat');
        Route::get('/get_group_chat_runtime', 'phone_quote\Group\GroupController@get_group_chat_runtime')->name('get_group_chat_runtime');
        Route::get('/view_group_chat', 'phone_quote\Group\GroupController@view_group_chat')->name('view_group_chat');
        Route::post('/save_group_chat', 'phone_quote\Group\GroupController@save_group_chat')->name('save_group_chat');
        Route::get('/get_chat_group_unread', 'phone_quote\Group\GroupController@get_chat_group_unread')->name('get_chat_group_unread');


        //ichat
        Route::get('/chats', 'phone_quote\global_chat\ChatController@index');
        Route::get('/chats/user/{id}', 'phone_quote\global_chat\ChatController@index3');
        Route::get('/chats/group/{id}', 'phone_quote\global_chat\ChatController@index3');
        Route::get('/get-chats', 'phone_quote\global_chat\ChatController@index22');
        Route::get('/chats/shipA1', 'phone_quote\global_chat\ChatController@shipA1Chats');
        Route::get('/read_msg', 'phone_quote\global_chat\ChatController@readMsgs');
        Route::post('/save_chat', 'phone_quote\global_chat\ChatController@save_chat')->name('save_chat');
        Route::get('/get_chat', 'phone_quote\global_chat\ChatController@get_chat')->name('get_chat');
        Route::get('/get_chat_runtime', 'phone_quote\global_chat\ChatController@get_chat_runtime')->name('get_chat_runtime');
        Route::get('/get_chat2', 'phone_quote\global_chat\ChatController@get_chat2')->name('get_chat2');
        Route::get('/view_chat_timer', 'phone_quote\global_chat\ChatController@view_chat_timer')->name('view_chat_timer');
        Route::get('/view_chat', 'phone_quote\global_chat\ChatController@view_chat')->name('view_chat');
        Route::get('/get_chat_unread', 'phone_quote\global_chat\ChatController@get_chat_unread')->name('get_chat_unread');

        Route::get('/chat-noti', 'phone_quote\global_chat\ChatController@chatNoti');
        Route::get('/chat-noti-count', 'phone_quote\global_chat\ChatController@chatNotiCount');

        //issue
        Route::get('/issues_add', 'issues\IssuesController@issues_add')->name('issues_add');
        Route::post('/save_issue', 'issues\IssuesController@save_issue')->name('save_issue');
        Route::get('/my_issues', 'issues\IssuesController@my_issues')->name('my_issues');
        Route::get('admin_issues', 'issues\IssuesController@admin_issues')->name('admin_issues');
        Route::get('issue_approve/{id}', 'issues\IssuesController@issue_approve')->name('issue_approve');
        Route::get('issue_reject/{id}', 'issues\IssuesController@issue_reject')->name('issue_reject');
        Route::get('get_notification', 'issues\IssuesController@get_notification')->name('get_notification');
        Route::get('issue_comments_list', 'issues\IssuesController@issue_comments_list')->name('issue_comments_list');
        Route::get('issue_comments_add/{id}', 'issues\IssuesController@issue_comments_add')->name('issue_comments_add');
        Route::post('/issue_comments_store', 'issues\IssuesController@issue_comments_store')->name('issue_comments_store');
        Route::get('issue_comments_done/{id}', 'issues\IssuesController@issue_comments_done')->name('issue_comments_done');
        Route::get('issue_view_admin/{id}', 'issues\IssuesController@issue_view_admin')->name('issue_view_admin');
        Route::post('/issue_penalty_store', 'issues\IssuesController@issue_penalty_store')->name('issue_penalty_store');
        Route::get('/all_notification', 'phone_quote\NewQuote@all_notification')->name('all_notification');


        //call history save
        Route::post('/call_history_post', 'phone_quote\callhistory\CallHistory@call_history_post')->name('call_history_post');
        Route::post('/call_history_post_2', 'phone_quote\callhistory\CallHistory@call_history_post_2')->name('call_history_post_2');
        Route::get('/show_call_history', 'phone_quote\callhistory\CallHistory@show_call_history');
        Route::get('/show_last_two_history', 'phone_quote\callhistory\CallHistory@show_last_two_history');
        Route::get('/show_pop_up', 'phone_quote\callhistory\CallHistory@show_pop_up');
        Route::post('/call_history_post_relist', 'phone_quote\callhistory\CallHistory@call_history_post_relist')->name('call_history_post_relist');
        Route::get('/get_pickup_date', 'phone_quote\callhistory\CallHistory@get_pickup_date');
        Route::get('/qa_update_history', 'phone_quote\callhistory\CallHistory@qa_update_history');
        Route::get('/qa_show_history', 'phone_quote\callhistory\CallHistory@qa_show_history');
        Route::post('/update_qa_remarks', 'phone_quote\callhistory\CallHistory@update_qa_remarks')->name('update_qa_remarks');
        Route::post('/qa_admin_remarks', 'phone_quote\callhistory\CallHistory@qa_admin_remarks')->name('qa_admin_remarks');
        Route::get('/allowQuotesDD', 'phone_quote\callhistory\CallHistory@allowQuotesDD')->name('allowQuotesDD');


        ///add by faisal
        ///update carrier
        Route::get('/carrier_add/{id?}', 'phone_quote\carrier\CarrierController@carrier_add');
        Route::get('/carrier/mcno/status', 'phone_quote\carrier\CarrierController@checkMcnoStatus')->name('check.mcno.status');
        Route::post('/store_carrier', 'phone_quote\carrier\CarrierController@store_carrier');
        Route::get('/get_carrier', 'phone_quote\carrier\CarrierController@get_carrier');
        Route::get('/get_carrier2', 'phone_quote\carrier\CarrierController@get_carrier2');
        Route::get('/getonecarrier', 'phone_quote\carrier\CarrierController@getonecarrier');
        Route::get('/get_sheet', 'phone_quote\carrier\CarrierController@get_sheet');
        Route::get('/carrier_add_new', 'phone_quote\carrier\CarrierController@carrier_add_new');
        Route::post('/store_carrier222', 'phone_quote\carrier\CarrierController@store_carrier222');

        //click to count
        Route::get('/count_user', 'phone_quote\callhistory\CallHistory@count_user');
        Route::get('/click_to_count', 'phone_quote\callhistory\CallHistory@click_to_count');
        Route::get('/fetch_count', 'phone_quote\callhistory\CallHistory@fetch_count');
        Route::get('/get_history_by_user_order', 'phone_quote\callhistory\CallHistory@get_history_by_user_order');

        //click carrier

        Route::get('/count_carrier', 'phone_quote\carrier\CarrierController@count_carrier');
        Route::get('/carrier_history', 'phone_quote\carrier\CarrierController@carrier_history');
        Route::post('/carrier_history_post', 'phone_quote\carrier\CarrierController@carrier_history_post');



        Route::get('/get_carrier_by_location', 'phone_quote\carrier\CarrierController@get_carrier_by_location')->name('get_carrier_by_location');
        Route::get('/get_storage_by_location', 'phone_quote\carrier\CarrierController@get_storage_by_location')->name('get_storage_by_location');
        Route::get('/find_carrier', 'phone_quote\carrier\CarrierController@find_carrier')->name('find_carrier');
        Route::get('/assign_find_carrier', 'phone_quote\carrier\CarrierController@assign_find_carrier')->name('assign_find_carrier');
        Route::get('/get_carrier_name', 'phone_quote\carrier\CarrierController@get_carrier_name');
        Route::get('/get_carrier_detail', 'phone_quote\carrier\CarrierController@get_carrier_detail');




        //COUNT DAY
        Route::get('/day_count', 'phone_quote\callhistory\CallHistory@day_count');
        Route::get('/fetch_day', 'phone_quote\callhistory\CallHistory@fetch_day');
        Route::get('/fetch_day2', 'phone_quote\callhistory\CallHistory@fetch_day2');
        Route::get('/fetch_day22', 'phone_quote\callhistory\CallHistory@fetch_day22');
        Route::get('/quote_listing', 'phone_quote\callhistory\CallHistory@quote_listing');

        Route::get('/shipment_status', 'phone_quote\callhistory\CallHistory@shipment_status');
        Route::get('/shipment_status_load', 'phone_quote\callhistory\CallHistory@shipment_status_load');
        Route::get('/get_shipment_status_order_detail', 'phone_quote\callhistory\CallHistory@get_shipment_status_order_detail');
        Route::get('/get_shipment_status_order_detail2', 'phone_quote\callhistory\CallHistory@get_shipment_status_order_detail2');
        Route::get('/get_shipment_status_order_detail3', 'phone_quote\callhistory\CallHistory@get_shipment_status_order_detail3');
        Route::post('/request_shipment', 'phone_quote\callhistory\CallHistory@request_shipment')->name('request_shipment');
        //new order
        Route::get('/new', 'phone_quote\neworder\NewOrder@new');
        Route::get('/fetch_data', 'phone_quote\neworder\NewOrder@fetch_data');
        Route::get('/return_data', 'phone_quote\neworder\NewOrder@return_data');
        Route::get('/new_edit/{id}', 'phone_quote\NewQuote@new_edit');
        Route::get('/pickup_approve/{id}', 'phone_quote\callhistory\CallHistory@pickup_approve');
        Route::get('/deliver_approve/{id}', 'phone_quote\callhistory\CallHistory@deliver_approve');
        Route::post('/schedule_delivery', 'phone_quote\callhistory\CallHistory@schedule_delivery');

        //Followup order
        Route::get('/followup', 'phone_quote\neworder\NewOrder@new');
        Route::get('/onapproval', 'phone_quote\neworder\NewOrder@new');
        Route::get('/onapproval_cancel', 'phone_quote\neworder\NewOrder@new');
        Route::get('/interested', 'phone_quote\neworder\NewOrder@new');
        Route::get('/asking_low', 'phone_quote\neworder\NewOrder@new');
        Route::get('/not_interested', 'phone_quote\neworder\NewOrder@new');
        Route::get('/not_responding', 'phone_quote\neworder\NewOrder@new');
        Route::get('/time_quote', 'phone_quote\neworder\NewOrder@new');
        Route::get('/payment_missing', 'phone_quote\neworder\NewOrder@new');
        Route::get('/booked', 'phone_quote\neworder\NewOrder@new');
        Route::get('/listed', 'phone_quote\neworder\NewOrder@new');
        Route::get('/dispatch', 'phone_quote\neworder\NewOrder@new');
        Route::get('/picked_up_approval', 'phone_quote\neworder\NewOrder@new');
        Route::get('/picked_up', 'phone_quote\neworder\NewOrder@new');
        Route::get('/deliver_approval', 'phone_quote\neworder\NewOrder@new');
        Route::get('/schedule_for_delivery', 'phone_quote\neworder\NewOrder@new');
        Route::get('/delivered', 'phone_quote\neworder\NewOrder@new');
        Route::get('/completed', 'phone_quote\neworder\NewOrder@new');
        Route::get('/cancel', 'phone_quote\neworder\NewOrder@new');
        Route::get('/deleted', 'phone_quote\neworder\NewOrder@new');
        Route::get('/owns_money', 'phone_quote\neworder\NewOrder@new');
        Route::post('/carrierupdate/{id}', 'phone_quote\carrier\CarrierController@carrier_add');
        Route::get('/searchData', 'phone_quote\neworder\NewOrder@searchData2');
        Route::get('/searchFetch', 'phone_quote\neworder\NewOrder@searchData');
        Route::get('/approaching', 'phone_quote\neworder\NewOrder@new');
        Route::get('/autos_approach', 'phone_quote\neworder\NewOrder@autosApproach')->name('autos.approach');
        Route::get('/get_category_count', 'phone_quote\neworder\NewOrder@getCategoryCount')->name('autos.approach.CategoryCount');
        Route::post('/autos_approach/admin/store', 'phone_quote\neworder\NewOrder@AllAutosApproachStore')->name('Admin.Approach.Store');
        Route::post('/autos_approach/check/validation', 'phone_quote\neworder\NewOrder@validateField')->name('autosApproach.validation.check');
        Route::get('/autos_approach/search', 'phone_quote\neworder\NewOrder@autosApproachSearch')->name('autos.approach.search');
        Route::post('/autos_approach/add/email', 'phone_quote\neworder\NewOrder@autosApproachEmailAdd')->name('autosApproach.save.email');
        Route::post('/autos_approach/add/category', 'phone_quote\neworder\NewOrder@autosApproachCategoryAdd')->name('autosApproach.save.category');
        Route::post('/autos_approach/store', 'phone_quote\neworder\NewOrder@storeAutosApproach')->name('store.autos.approach');
        Route::get('/autos_approach/all', 'phone_quote\neworder\NewOrder@allAutosApproach')->name('all.autos.approach');
        Route::get('/autos_approach/filter', 'phone_quote\neworder\NewOrder@filterAssignedAutos')->name('filter.assigned.autosApproach');
        Route::get('/autos_approach/call_count', 'phone_quote\neworder\NewOrder@addCallCount')->name('add.call.count');
        Route::get('/autos_approach/whatsapp_count', 'phone_quote\neworder\NewOrder@addWhatsappCount')->name('autosapproach.Whatsapp.count');
        Route::get('/autos_approach/whatsapp_count/all', 'phone_quote\neworder\NewOrder@approachWhatsappCount')->name('all.autosapproach.whatsapp');
        Route::post('/company_call_history/store', 'phone_quote\neworder\NewOrder@storeHistory')->name('store.call.history');
        Route::get('/get/call/history', 'phone_quote\neworder\NewOrder@getHistory')->name('get.call.history');
        Route::get('/edit/allowed-states', 'phone_quote\neworder\NewOrder@editAllowedStates')->name('edit.allowed.states');
        Route::get('/filter/allowed-states', 'phone_quote\neworder\NewOrder@filterUsedAndNew')->name('filter.usedAndNew.data');
        Route::get('/double_booking', 'phone_quote\neworder\NewOrder@double_booking');
        Route::get('/double_booking_load', 'phone_quote\neworder\NewOrder@double_booking_load');
        Route::get('/double_booking_load_data', 'phone_quote\neworder\NewOrder@double_booking_load_data');

        Route::get('/vidio', 'phone_quote\vidio\VidoController@index');
        Route::get('/uploadvidioadd', 'phone_quote\vidio\VidioController@uploadvidioadd');

        Route::post('/notes_save', 'phone_quote\neworder\NewOrder@notes_save');
        Route::get('/get_notes', 'phone_quote\neworder\NewOrder@get_notes');
        Route::get('/delete_notes', 'phone_quote\neworder\NewOrder@delete_notes');

        // List all email templates
        Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
        Route::get('email-templates', [EmailTemplateController::class, 'index'])->name('email-templates.index');
        // Show the form to create a new email template
        Route::get('email-templates/create', [EmailTemplateController::class, 'create'])->name('email-templates.create');
        // Store a new email template
        Route::post('email-templates', [EmailTemplateController::class, 'store'])->name('email-templates.store');
        // Show a specific email template
        Route::get('email-templates/{id}', [EmailTemplateController::class, 'show'])->name('email-templates.show');
        // Show the form to edit a specific email template
        Route::get('email-templates/{id}/edit', [EmailTemplateController::class, 'edit'])->name('email-templates.edit');
        // Update a specific email template
        Route::put('email-templates/{id}/update', [EmailTemplateController::class, 'update'])->name('email-templates.update');
        // Delete a specific email template
        Route::delete('email-templates/{id}', [EmailTemplateController::class, 'destroy'])->name('email-templates.destroy');
        // Send a specific email to single user
        Route::get('send-user-email', [EmailTemplateController::class, 'sendEmail'])->name('send.user.mail');
        Route::get('send-user-email2', [EmailTemplateController::class, 'sendEmail2'])->name('send.user.mail2');
        Route::get('getEmailTemplates', [EmailTemplateController::class, 'getEmailTemplates'])->name('get.email.templates');

        // cpanel emails credentials
        Route::get('cpanelemails', [CpanelEmailController::class, 'index'])->name('cpanelemails.index');
        Route::get('cpanelemails/create', [CpanelEmailController::class, 'create'])->name('cpanelemails.create');
        Route::post('cpanelemails', [CpanelEmailController::class, 'store'])->name('cpanelemails.store');
        Route::get('cpanelemails/{id}/edit', [CpanelEmailController::class, 'edit'])->name('cpanelemails.edit');
        Route::post('cpanelemails/{id}', [CpanelEmailController::class, 'update'])->name('cpanelemails.update');
        Route::delete('cpanelemails/{id}', [CpanelEmailController::class, 'destroy'])->name('cpanelemails.destroy');

        // Sending custom email
        Route::get('email-templates/custom/{id}', [SendCustomEmail::class, 'sendCustomMail'])->name('custom.email-send');

        // Add Custom Nature
        Route::get('customer-nature', 'phone_quote\neworder\NewOrder@customerNatureAdd');

        // Store Custom Nature
        Route::post('customer-nature/store', 'phone_quote\neworder\NewOrder@customerNatureStore')->name('customerNature.store');


        Route::post('/penal_type', 'phone_quote\neworder\NewOrder@penal_type');
        Route::post('/call_type', 'phone_quote\neworder\NewOrder@call_type');

        Route::get('/sales_report', 'phone_quote\admin_reports\AdminReportController@sales_report');
        Route::post('/sales_report_2', 'phone_quote\admin_reports\AdminReportController@sales_report');

        Route::get('/fetch_sales_data', 'phone_quote\admin_reports\AdminReportController@fetch_sales_data');
        Route::get('/fetch_sales_data2', 'phone_quote\admin_reports\AdminReportController@fetch_sales_data2');

        Route::get('/general_setting_add', 'DashboardController@general_setting_add');
        Route::post('/store_general_setting', 'DashboardController@store_general_setting');
        //invoice
        Route::get('/invoice_list', 'phone_quote\management\ManagementController@invoice_list');
        Route::post('/invoice_list_2', 'phone_quote\management\ManagementController@invoice_list');
        Route::get('/invoice_add', 'phone_quote\management\ManagementController@invoice_add');
        Route::post('/store_invoice', 'phone_quote\management\ManagementController@store_invoice');
        Route::get('/view_invoice/{id}', 'phone_quote\management\ManagementController@view_invoice');

        //roro invoice
        Route::get('/invoice_list_roro', 'phone_quote\management\ManagementController@invoice_list_roro');
        Route::post('/invoice_list_roro_2', 'phone_quote\management\ManagementController@invoice_list_roro');
        Route::get('/invoice_add_roro', 'phone_quote\management\ManagementController@invoice_add_roro');
        Route::post('/store_invoice_roro', 'phone_quote\management\ManagementController@store_invoice_roro');
        Route::get('/view_invoice_roro/{id}', 'phone_quote\management\ManagementController@view_invoice_roro');

        //storage
        Route::get('/storage_list', 'phone_quote\management\ManagementController@storage_list');
        Route::get('/storage_order_list', 'phone_quote\management\ManagementController@storage_order_list');
        Route::get('/storage_add', 'phone_quote\management\ManagementController@storage_add');
        Route::post('/store_update_storage', 'phone_quote\management\ManagementController@store_update_storage');
        Route::post('/store_storage', 'phone_quote\management\ManagementController@store_storage');
        Route::get('/storage_edit/{id}', 'phone_quote\management\ManagementController@storage_edit');
        Route::post('/update_storage/{id}', 'phone_quote\management\ManagementController@update_storage');
        Route::get('/view_storage/{id}', 'phone_quote\management\ManagementController@view_storage');
        Route::get('/order-storage/{id}', 'phone_quote\management\ManagementController@orderStorage');
        Route::get('/storage_data', 'phone_quote\management\ManagementController@storage_data');
        Route::get('/storage_data_get', 'phone_quote\management\ManagementController@storage_data_get');
        Route::get('/updatePickupCarrier', 'phone_quote\management\ManagementController@updatePickupCarrier');
        Route::post('/update_pickup_carreir', 'phone_quote\management\ManagementController@update_pickup_carreir');


        //carrier1
        Route::get('/carrier_list', 'phone_quote\carrier\CarrierController@carrier_list');
        Route::post('/block-carrier/status', 'phone_quote\carrier\CarrierController@blockCarrier')->name('block.carrier.status');
        Route::get('/block-carrier/get', 'phone_quote\carrier\CarrierController@blockCarrierGet')->name('block.carrier.getAll');
        Route::get('/block-carrier/search/carrier', 'phone_quote\carrier\CarrierController@getSearchCarriers')->name('get.search.carriers');
        Route::get('/customer_list', 'phone_quote\customer\CustomerController@customer_list');
        Route::post('/customer_list_2', 'phone_quote\customer\CustomerController@customer_list_2');
        Route::post('/customer_data', 'phone_quote\customer\CustomerController@customer_data');
        Route::post('/customer-order-history-update', 'phone_quote\customer\CustomerController@customerOrderHistoryUpdate');
        Route::get('/show-customer-order-history', 'phone_quote\customer\CustomerController@customerOrderHistoryShow');
        Route::get('/customer_list_2', 'phone_quote\customer\CustomerController@redirect_customer_list');

        //carrier2

        Route::get('/carrier_list2', 'phone_quote\carrier\CarrierController@carrier_list2');




        Route::get('/owes_money_update/{id}', 'phone_quote\NewQuote@owes_money_update');
        Route::post('/owes_history_update', 'phone_quote\NewQuote@owes_history_update');
        Route::get('/owes_history_view', 'phone_quote\NewQuote@owes_history_view');
        Route::post('/store_payment_confirmed', 'phone_quote\NewQuote@store_payment_confirmed');
        Route::post('/store_payment_status', 'phone_quote\NewQuote@store_payment_status');

        Route::get('/addAchieveTarget', 'phone_quote\NewQuote@addAchieveTarget')->name('addAchieveTarget');
        Route::post('/storeAchieveTarget', 'phone_quote\NewQuote@storeAchieveTarget')->name('storeAchieveTarget');

        //attendance
        Route::get('/attendance_report', 'phone_quote\attendance\AttendanceController@attendance_report')->name('attendance_report');
        Route::post('/attendance_report2', 'phone_quote\attendance\AttendanceController@attendance_report');
        Route::get('/fetch_attendance_data', 'phone_quote\attendance\AttendanceController@fetch_attendance_data');

        //Inactivity
        Route::get('/count_activity', 'phone_quote\inactivity\Inactivity@count_activity');
        Route::get('/total_activity', 'phone_quote\inactivity\Inactivity@total_activity');


        Route::get('/report_terminal', 'phone_quote\management\ManagementController@report_terminal');
        Route::get('/profile_card_data', 'phone_quote\management\ManagementController@profile_card_data')->name('profile_card_data');
        Route::post('/profile_card_list', 'phone_quote\management\ManagementController@profile_card_list')->name('profile_card_list');
        Route::get('/profile-card/{id}/auctions', 'phone_quote\management\ManagementController@getAuctionDetails');


        Route::get('/fetch_terminal_data', 'phone_quote\management\ManagementController@fetch_terminal_data');
        Route::get('/fetch_terminal_data2', 'phone_quote\management\ManagementController@fetch_terminal_data2');

        Route::get('/chart_view', 'DashboardController@chart_view');

        Route::get('/get_web_price', 'phone_quote\NewQuote@get_central');
        Route::get('/web_price', 'phone_quote\NewQuote@show_central');
        Route::post('/save_web_price', 'phone_quote\NewQuote@save_central');


        Route::post('/trash_order', 'phone_quote\neworder\NewOrder@trash_order');


        Route::get('/get_months_chart', 'DashboardController@get_months_chart');

        Route::get('/credit_card_list', 'phone_quote\customer\CustomerController@credit_card_list');
        Route::post('/credit_card_list2', 'phone_quote\customer\CustomerController@credit_card_list');


        Route::get('/user_commission', 'phone_quote\user_commission\UserCommissionController@index');
        Route::post('/user_commission_2', 'phone_quote\user_commission\UserCommissionController@index');
        Route::get('/first_bonus', 'phone_quote\user_commission\UserCommissionController@first_bonus');
        Route::post('/first_bonus_2', 'phone_quote\user_commission\UserCommissionController@first_bonus');
        Route::post('/save_first_bonus', 'phone_quote\user_commission\UserCommissionController@save_first_bonus');
        Route::get('/second_bonus', 'phone_quote\user_commission\UserCommissionController@second_bonus');
        Route::post('/second_bonus_2', 'phone_quote\user_commission\UserCommissionController@second_bonus');
        Route::post('/save_second_bonus', 'phone_quote\user_commission\UserCommissionController@save_second_bonus');

        Route::get('/cancel_bonus', 'phone_quote\user_commission\UserCommissionController@cancel_bonus');
        Route::post('/cancel_bonus_2', 'phone_quote\user_commission\UserCommissionController@cancel_bonus');
        Route::post('/save_cancel_bonus', 'phone_quote\user_commission\UserCommissionController@save_cancel_bonus');

        Route::post('/post_commision', 'phone_quote\user_commission\UserCommissionController@post_commision');
        Route::get('/cancel_orders', 'phone_quote\user_commission\UserCommissionController@cancel_orders');


        Route::post('/save_order_history', 'phone_quote\callhistory\CallHistory@call_history_post')->name('save_order_history');

        Route::get('/get_auction', 'phone_quote\NewQuote@get_auction')->name('get_auction');

        Route::get('/customerReviews', 'phone_quote\NewQuote@customerReviews')->name('customer.reviews');



        Route::get('/old_previous', 'phone_quote\neworder\NewOrder@old_previous');
        Route::get('/old_shipa1', 'phone_quote\neworder\NewOrder@old_shipa1');
        Route::get('/return_data_shipa1', 'phone_quote\neworder\NewOrder@return_data_shipa1');
        Route::get('/old_cards_shipa1', 'phone_quote\neworder\NewOrder@old_cards_shipa1');

        Route::get('/move_to_new/{id}', 'phone_quote\neworder\NewOrder@move_to_new');

        Route::get('/manage_payments', 'phone_quote\neworder\NewOrder@manage_payments');

        Route::get('/payments/{id}', 'phone_quote\neworder\NewOrder@payments');
        Route::post('/store_profit', 'phone_quote\neworder\NewOrder@store_profit');

        Route::get('/profit_listing', 'phone_quote\neworder\NewOrder@profit_listing');

        Route::get('/payment_recieved', 'phone_quote\neworder\NewOrder@payment_recieved');

        Route::get('/employee_order', 'phone_quote\neworder\NewOrder@employee_order');
        Route::get('/fetch_employee_order', 'phone_quote\neworder\NewOrder@fetch_employee_order');




        Route::get('/fetch_data_profit', 'phone_quote\neworder\NewOrder@fetch_data_profit');

        Route::get('/fetch_manage_payments', 'phone_quote\neworder\NewOrder@fetch_manage_payments');

        Route::get('/mark_as_paid/{id}', 'phone_quote\neworder\NewOrder@mark_as_paid');

        Route::post('/paid_status', 'phone_quote\neworder\NewOrder@paid_status');

        //logout all accounts automatically at 5AM except admin

        //question answers
        Route::get('/questions', 'phone_quote\Question\QuestionController@index');
        Route::get('/questions/get', 'phone_quote\Question\QuestionController@get');
        Route::post('/questions/store', 'phone_quote\Question\QuestionController@store');
        Route::post('/questions/show', 'phone_quote\Question\QuestionController@show');
        Route::post('/questions/destroy', 'phone_quote\Question\QuestionController@destroy');
        Route::get('/questions/edit', 'phone_quote\Question\QuestionController@edit');
        Route::post('/questions/update', 'phone_quote\Question\QuestionController@update');

        //show data
        Route::get('/show-data', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/search', 'phone_quote\Question\QuestionController@showData2');
        Route::get('/show-data/new', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/followup', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/interested', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/not_interested', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/asking_low', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/not_responding', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/time_quote', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/payment_missing', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/on_approval', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/on_approval_cancel', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/booked', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/deleted', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/owes_money', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/listed', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/schedule', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/not-pickedup', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/pickedup', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/not-delivered', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/schedule-for-delivery', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/delivered', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/complete', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/cancel', 'phone_quote\Question\QuestionController@showData');
        Route::get('/show-data/auction_not_win', 'phone_quote\Question\QuestionController@showData');
        Route::post('/show-data/update-auction', 'phone_quote\Question\QuestionController@updateAuction');
        Route::get('/notRes', 'phone_quote\Question\QuestionController@notRes');

        Route::get('/searchFilter', 'phone_quote\Question\QuestionController@searchFilter');
        Route::get('/qna-modal', 'phone_quote\Question\QuestionController@qnaModal');
        Route::get('/ques-ans', 'phone_quote\Question\QuestionController@answers');
        Route::get('/send-message', 'phone_quote\Question\QuestionController@sendMessage');
        Route::get('/msg', 'phone_quote\Question\QuestionController@msg');
        Route::post('/add-port', 'phone_quote\Question\QuestionController@addPort');
        Route::get('/edit-port', 'phone_quote\Question\QuestionController@editPort');
        Route::post('/update-port', 'phone_quote\Question\QuestionController@updatePort');
        Route::get('/delete-port', 'phone_quote\Question\QuestionController@deletePort');

        Route::post('/add-message-call', 'phone_quote\customer\CustomerController@addMsgCall');
        Route::get('/show-message-call', 'phone_quote\customer\CustomerController@showMsgCall');

        Route::get('/phone_digits', 'phone_quote\NewQuote@phoneDigits');
        Route::post('/update_phone_digits', 'phone_quote\NewQuote@updatePhoneDigits');

        Route::post('/custom-chat-user', 'phone_quote\Question\QuestionController@chatCenterUser');
        Route::post('/show-chat-center', 'phone_quote\Question\QuestionController@showChatCenter');
        Route::post('/show-chat-center2', 'phone_quote\Question\QuestionController@showChatCenter2');
        Route::post('/send-custom-chat', 'phone_quote\Question\QuestionController@sendCustomChat');
        Route::post('/exit-chat', 'phone_quote\Question\QuestionController@exitChat');
        Route::post('/open-chat', 'phone_quote\Question\QuestionController@openChat');
        Route::post('/get-auto-chat', 'phone_quote\Question\QuestionController@getAutoChat');
        Route::post('/get-auto-chat2', 'phone_quote\Question\QuestionController@getAutoChat2');
        Route::post('/get-auto-convo', 'phone_quote\Question\QuestionController@getAutoConvo');
        Route::post('/read-chat', 'phone_quote\Question\QuestionController@readChat');

        Route::post('/public-chat-user', 'phone_quote\Question\QuestionController@publicCenterUser');
        Route::post('/public-chat-user2', 'phone_quote\Question\QuestionController@publicCenterUser2');
        Route::post('/exit-public-chat', 'phone_quote\Question\QuestionController@exitPublicChat');
        Route::post('/send-public-chat', 'phone_quote\Question\QuestionController@sendPublicChat');
        Route::post('/read-public-chat', 'phone_quote\Question\QuestionController@readPublicChat');

        Route::get('/transfer-quotes', 'phone_quote\customer\CustomerController@transferQuotes');
        Route::post('/transfer-quotes/update', 'phone_quote\customer\CustomerController@transferQuotesStore');
        Route::post('/transfer-quotes/single', 'phone_quote\customer\CustomerController@transferSingleQuotesStore');
        Route::post('/search-ot-dis', 'phone_quote\customer\CustomerController@searchOtDis');
        Route::post('/search-ot-dis2', 'phone_quote\customer\CustomerController@searchOtDis2');
        Route::get('/search-for-revert', 'phone_quote\customer\CustomerController@searchForRevert');
        Route::post('/revert-the-quotes', 'phone_quote\customer\CustomerController@revertTheQuotes');

        Route::get('/revenue', 'CustomerRevenue@index');
        Route::post('/revenue/search', 'CustomerRevenue@search');
        Route::get('/dispatch_report', 'CustomerRevenue@index2');
        Route::post('/dispatch_report/search', 'CustomerRevenue@search2');
        Route::get('/performance_report', 'CustomerRevenue@index3');
        Route::post('/performance_report/search', 'CustomerRevenue@search3');
        Route::get('/qa_report', 'CustomerRevenue@index4');
        Route::post('/qa_report/search', 'CustomerRevenue@search4');
        Route::get('/total_records', 'CustomerRevenue@total_records');
        Route::get('/jd_report', 'CustomerRevenue@jd_report');

        Route::get('/coupons', 'CustomerRevenue@show');
        Route::get('/coupons/create', 'CustomerRevenue@store');
        Route::get('/coupon_number', 'CustomerRevenue@coupon_number');

        Route::get('/feedback', 'CustomerRevenue@feedback');
        Route::get('/feedback/show', 'CustomerRevenue@feedbackGet');
        Route::get('/feedback/create', 'CustomerRevenue@feedbackStore');

        Route::get('/website-links', 'WebsiteLinkController@index');
        Route::post('/website-links/create', 'WebsiteLinkController@store');
        Route::get('/website-links/edit', 'WebsiteLinkController@edit');
        Route::get('/website-links/destroy/{id}', 'WebsiteLinkController@destroy');
        Route::get('/send-website-link', 'WebsiteLinkController@sendLink');

        Route::get('/manager', 'ManagerGroupController@index');
        Route::get('/managers-group/{id}', 'ManagerGroupController@show');
        Route::post('/managers-group/calling-button', 'ManagerGroupController@store');
        Route::post('/assign_daily_qoutes/{id}', 'ManagerGroupController@update');

        Route::get('/update-carrier-history', 'phone_quote\NewQuote@updateCarrierHistory');
        Route::get('/view-carrier-history', 'phone_quote\NewQuote@viewCarrierHistory');

        Route::get('/user_rating', 'phone_quote\NewQuote@userRating');
        Route::get('/ratingdetail', 'phone_quote\NewQuote@ratingdetail');
        Route::get('/rating_count', 'phone_quote\NewQuote@rating_count');
        Route::post('/ratingdetail/create', 'phone_quote\NewQuote@ratingdetailcreate');
        Route::get('/order_users', 'phone_quote\NewQuote@order_users');
        Route::get('/qa_count', 'phone_quote\NewQuote@qa_count');
        Route::get('/approach_count', 'phone_quote\NewQuote@approach_count');

        Route::get('/sheets_data/{id}', 'ReportsController@sheets_data');
        Route::get('/sheets_list', 'ReportsController@sheets_list');
        Route::post('/create_sheet_google', 'ReportsController@create_sheet_google');

        Route::get('/port_price', 'PortPriceController@index');
        Route::get('/port_price/create', 'PortPriceController@create');
        Route::post('/port_price/store', 'PortPriceController@store');
        Route::get('/port_price/edit/{id}', 'PortPriceController@edit');
        Route::post('/port_price/update/{id}', 'PortPriceController@update');
        Route::get('/port_price/show/{id}', 'PortPriceController@show');
        Route::get('/port_price/destroy/{id}', 'PortPriceController@destroy');

        Route::get('/demand_order', 'DemandController@index');
        Route::get('/demand_order/add', 'DemandController@add');
        Route::post('/demand_order/store/{id}', 'DemandController@store');
        Route::post('/demand_order/update/{id}', 'DemandController@update');

        Route::get('/sell_invoice', 'SellInvoiceController@index');
        Route::get('/sell_invoice/create', 'SellInvoiceController@create');
        Route::post('/sell_invoice/store', 'SellInvoiceController@store');
        Route::get('/sell_invoice/edit/{id}', 'SellInvoiceController@edit');
        Route::post('/sell_invoice/update/{id}', 'SellInvoiceController@update');
        Route::get('/sell_invoice/invoice/{id}', 'SellInvoiceController@invoice');

        Route::get('/port_tracking', 'PortTrackingController@index');
        Route::get('/add/port-history', 'PortTrackingController@add_history')->name('add.port.history');
        Route::get('/get/status-port', 'PortTrackingController@getStatusPort')->name('get.status.port');

        Route::get('/profile', 'ProfileController@index')->middleware('password.confirm');
        Route::get('/profile/show', 'ProfileController@show');

        Route::post('/assign_to_dispatcher', 'phone_quote\NewQuote@assign_to_dispatcher');
        Route::get('/ot_commission', 'phone_quote\user_commission\UserCommissionController@ot_commission');
        Route::get('/excelsheet/{slug}', 'ReportsController@excelsheet');
        Route::post('/special_instructions', 'ReportsController@special_instructions');
        Route::post('/carrier_approachings/store', 'ReportsController@carrier_approachings')->name('store.carrier_approachings');
        Route::get('/carrier_approachings/get', 'ReportsController@getCarrier_approachings')->name('get.carrier_approachings');
        Route::post('/request_shipment_reply/{id}', 'phone_quote\callhistory\CallHistory@request_shipment_reply');
    });

    Route::group(['middleware' => ['code-giver']], function () {
        Route::get('/employees', 'DashboardController@employees')->name('employees');
        Route::get('/update_password2', 'DashboardController@update_password2')->name('update_password2');
        Route::post('/update_password_post2', 'DashboardController@update_password_post')->name('update_password_post2');
        Route::post('/increase_quotes', 'DashboardController@increase_quotes');
    });

    Route::group(['middleware' => ['chat-approver']], function () {
        Route::get('/custom-chat', 'DashboardController@customChat')->name('custom-chat');
        Route::post('/custom-chat2', 'DashboardController@customChat2');
        Route::post('/approve-chat', 'DashboardController@approveChat')->name('approve-chat');
        Route::post('/flag-chat', 'DashboardController@flagChat')->name('flag-chat');
        Route::post('/approve-public-chat', 'DashboardController@approvePublicChat')->name('approve-public-chat');
        Route::post('/approve-group-chat', 'DashboardController@approveGroupChat')->name('approve-group-chat');
        Route::post('/flag-public-chat', 'DashboardController@flagPublicChat')->name('flag-public-chat');
        Route::post('/freeze-active', 'DashboardController@freezeActive')->name('freeze-active');
        Route::get('/update_password2', 'DashboardController@update_password2')->name('update_password2');
        Route::post('/update_password_post2', 'DashboardController@update_password_post')->name('update_password_post2');

        Route::get('/get-chat-approver', 'DashboardController@getChatsForApprover');
        Route::post('/flag-to-approvers', 'DashboardController@flagToApprover');
        Route::post('/flag-to-public-approvers', 'DashboardController@flagToPublicApprover');
        Route::get('/get-count-of-chat', 'DashboardController@getCountOfChat');
        Route::post('/remove-flag', 'DashboardController@removeFlag')->name('remove-flag');
        Route::post('/remove-flag-chat', 'DashboardController@removeFlagChat')->name('remove-flag-chat');
        Route::post('/remove-flag-chat-public', 'DashboardController@removeFlagChatPublic')->name('remove-flag-chat-public');
        Route::post('/red-flag', 'DashboardController@redFlag')->name('red-flag');
    });

    // Route::get('/confirm-password', function () {
    //     return view('auth.confirm-password');
    // })->name('password.confirm');

    // Route::post('/confirm-password', function (Request $request) {
    //     $request->validate([
    //         'password' => 'required|string',
    //     ]);

    //     if (Hash::check($request->password, Auth::user()->profile_password)) {
    //         session(['auth.password_confirmed_at' => time()]);
    //         return redirect()->to('/profile');
    //     }

    //     if (empty($request->password)) {
    //         return redirect()->route('set.new.password')->with('message', 'Please set a new password.');
    //     }

    //     return back()->withErrors(['password' => 'Incorrect password']);
    // })->name('password.confirm.post');

    // Route::get('/set-new-password', function () {
    //     return view('auth.set-new-password');
    // })->name('set.new.password');

    // Route::post('/update-password', function (Request $request) {
    //     $request->validate([
    //         'new_password' => 'required|string|min:8|confirmed',
    //     ]);

    //     $user = Auth::user();
    //     $user->profile_password = Hash::make($request->new_password);
    //     $user->save();

    //     return redirect()->to('/profile')->with('status', 'Password updated successfully!');
    // })->name('password.update');

    Route::get('/confirm-password', function () {

        $user = Auth::user();

        if (empty($user->profile_password)) {
            return redirect()->route('set.new.password')->with('message', 'Please set a new password.');
        }

        return view('auth.confirm-password');
    })->name('password.confirm');

    Route::post('/confirm-password', function (Request $request) {
        $request->validate([
            'password' => 'required|string',
        ]);

        $user = Auth::user();

        if (Hash::check($request->password, $user->profile_password)) {
            session(['auth.password_confirmed_at' => time()]);
            return redirect()->to('/profile');
        }

        if (empty($user->profile_password)) {
            return redirect()->route('set.new.password')->with('message', 'Please set a new password.');
        }

        return back()->withErrors(['password' => 'Incorrect password']);
    })->name('password.confirm.post');

    Route::get('/set-new-password', function () {

        $user = Auth::user();

        if (!empty($user->profile_password)) {
            return redirect()->route('password.confirm')->with('message', 'Please confirm password.');
        }

        return view('auth.set-new-password');
    })->name('set.new.password');

    Route::post('/update-password', function (Request $request) {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();
        $user->profile_password = Hash::make($request->new_password);
        $user->save();

        return redirect()->to('/profile')->with('status', 'Password updated successfully!');
    })->name('password.update');

    //New Work By Mohsin
    Route::get('autos_approach_new1/{id}', 'phone_quote\neworder\NewOrder@autos_approach_new')->name('autos.autos_approach_new');

    Route::post('/autos_approach_new/store', 'phone_quote\neworder\NewOrder@storeAutosApproachNew')->name('store.autos.approach_new');

    Route::get('/autos_approach_new/search', 'phone_quote\neworder\NewOrder@autosApproachSearchNew')->name('autos.approach_new.search');

    Route::post('/autos_approach_new/add/email', 'phone_quote\neworder\NewOrder@autosApproachEmailAddNew')->name('autosApproachNew.save.email');

    Route::get('/autos_approach_new/whatsapp_count', 'phone_quote\neworder\NewOrder@autosApproachPhoneCount')->name('autosapproachnew.phone.count');

    Route::get('/autos_approach_new/history', 'phone_quote\neworder\NewOrder@autosApproachgetHistory')->name('autosapproachnew.call.history');

    Route::post('/autos_approach_new/history_store', 'phone_quote\neworder\NewOrder@autosApproachgetstoreHistory')->name('autosapproachnew.store.call.history');


    Route::get('/autos_approach_new/all', 'phone_quote\neworder\NewOrder@allNewAutosApproach')->name('all.autos.autos_approach_new');
    Route::get('/autos_approach_new/filter', 'phone_quote\neworder\NewOrder@filterNewAssignedAutos')->name('filter.assigned.autos_approach_new');
    Route::post('/autos_approach_new/admin/store', 'phone_quote\neworder\NewOrder@AllNewAutosApproachStore')->name('Admin.autos_approach_new.Store');
    Route::post('/autos_approach_new/check/validation', 'phone_quote\neworder\NewOrder@NewvalidateField')->name('autos_approach_new.validation.check');


    Route::get('/autos_approach_new_dealer', 'phone_quote\neworder\NewOrder@autos_approach_new_dealer')->name('autos.autos_approach_new_dealer');
    Route::post('/autos_approach_new/store_dealer', 'phone_quote\neworder\NewOrder@storeAutosApproachNew_dealer')->name('store.autos.approach_new_dealer');
    Route::get('/autos_approach_new/search_dealer', 'phone_quote\neworder\NewOrder@autosApproachSearchNew_dealer')->name('autos.approach_new.search_dealer');
    Route::post('/autos_approach_new/add/email_dealer', 'phone_quote\neworder\NewOrder@autosApproachEmailAddNew_dealer')->name('autosApproachNew.save.email_dealer');
    Route::get('/autos_approach_new/whatsapp_count_dealer', 'phone_quote\neworder\NewOrder@autosApproachPhoneCount_dealer')->name('autosapproachnew.phone.count_dealer');
    Route::get('/autos_approach_new/history_dealer', 'phone_quote\neworder\NewOrder@autosApproachgetHistory_dealer')->name('autosapproachnew.call.history_dealer');
    Route::post('/autos_approach_new/history_store_dealer', 'phone_quote\neworder\NewOrder@autosApproachgetstoreHistory_dealer')->name('autosapproachnew.store.call.history_dealer');


    Route::get('/autos_approach_new/all_dealer', 'phone_quote\neworder\NewOrder@allNewAutosApproach_dealer')->name('all.autos.autos_approach_new_dealer');
    Route::get('/autos_approach_new/filter_dealer', 'phone_quote\neworder\NewOrder@filterNewAssignedAutos_dealer')->name('filter.assigned.autos_approach_new_dealer');
    Route::post('/autos_approach_new/admin/store_dealer', 'phone_quote\neworder\NewOrder@AllNewAutosApproachStore_dealer')->name('Admin.autos_approach_new.Store_dealer');
    Route::post('/autos_approach_new/check/validation_dealer', 'phone_quote\neworder\NewOrder@NewvalidateField_dealer')->name('autos_approach_new.validation.check_dealer');


    Route::get('/autos_approach_new_carrier', 'phone_quote\neworder\NewOrder@autos_approach_new_carrier')->name('autos.autos_approach_new_carrier');
    Route::post('/autos_approach_new/store_carrier', 'phone_quote\neworder\NewOrder@storeAutosApproachNew_carrier')->name('store.autos.approach_new_carrier');
    Route::get('/autos_approach_new/search_carrier', 'phone_quote\neworder\NewOrder@autosApproachSearchNew_carrier')->name('autos.approach_new.search_carrier');
    Route::post('/autos_approach_new/add/email_carrier', 'phone_quote\neworder\NewOrder@autosApproachEmailAddNew_carrier')->name('autosApproachNew.save.email_carrier');
    Route::get('/autos_approach_new/whatsapp_count_carrier', 'phone_quote\neworder\NewOrder@autosApproachPhoneCount_carrier')->name('autosapproachnew.phone.count_carrier');
    Route::get('/autos_approach_new/history_carrier', 'phone_quote\neworder\NewOrder@autosApproachgetHistory_carrier')->name('autosapproachnew.call.history_carrier');
    Route::post('/autos_approach_new/history_store_carrier', 'phone_quote\neworder\NewOrder@autosApproachgetstoreHistory_carrier')->name('autosapproachnew.store.call.history_carrier');


    Route::get('/autos_approach_new/all_carrier', 'phone_quote\neworder\NewOrder@allNewAutosApproach_carrier')->name('all.autos.autos_approach_new_carrier');
    Route::get('/autos_approach_new/filter_carrier', 'phone_quote\neworder\NewOrder@filterNewAssignedAutos_carrier')->name('filter.assigned.autos_approach_new_carrier');
    Route::post('/autos_approach_new/admin/store_carrier', 'phone_quote\neworder\NewOrder@AllNewAutosApproachStore_carrier')->name('Admin.autos_approach_new.Store_carrier');
    Route::post('/autos_approach_new/check/validation_carrier', 'phone_quote\neworder\NewOrder@NewvalidateField_carrier')->name('autos_approach_new.validation.check_carrier');




    Route::post('/approaching_assign', 'phone_quote\neworder\NewOrder@storeApproachingAssign')->name('store.approaching_assign');

    Route::get('/approaching_reporting/Approaching_report', 'phone_quote\neworder\NewOrder@Approaching_report')->name('approaching_reporting');
    Route::get('/approaching_reporting/filter', 'phone_quote\neworder\NewOrder@filterNewAssignedApproaching_report')->name('filter.approaching_reporting');
    Route::get('/historyApproaching/history', 'phone_quote\neworder\NewOrder@historyApproaching')->name('history.historyApproaching');
    Route::get('/approaching_reporting/assign', 'phone_quote\neworder\NewOrder@approaching_assign_user')->name('assign.approaching_reporting_assign');

    Route::post('/approaching/save/email', 'phone_quote\neworder\NewOrder@approaching_save_email')->name('approaching.save.email');


    Route::get('/ringcentral/auth', 'RingCentralController@authenticate');
    Route::get('/ringcentral/callback','RingCentralController@callback');
    Route::get('/callback', 'OAuthController@handleCallback');
    Route::get('/ring_central_portal', 'RingCentralController@ring_central_portal');

    Route::get('/view_template', 'TemplateController@index')->name('view_template');
    Route::get('/template_datatable', 'TemplateController@template_datatable')->name('template_datatable');
    Route::post('/view_template_store', 'TemplateController@view_template_store')->name('view_template_store');
    Route::post('/updateProfileCard', 'phone_quote\neworder\NewOrder@updateProfileCard')->name('updateProfileCard');
    Route::get('/getProfileCard', 'phone_quote\neworder\NewOrder@getProfileCard')->name('getProfileCard');


    Route::prefix('orders/{order}')->group(function () {
        Route::post('pricing/check', [OrderPricingController::class, 'check'])->name('orders.pricing.check');
        Route::get('pricing/history', [OrderPricingController::class, 'history'])->name('orders.pricing.history');
    });

    // ── New Payment System (permission 164 = Admin, 165 = Agent) ─────────────

    // Admin Payment System (permission 164 or role=1)
    Route::get('/admin-payments', 'NewPaymentSystemController@index')->name('admin.payments.index');
    Route::get('/admin-payments/{id}', 'NewPaymentSystemController@show')->name('admin.payments.show');
    Route::post('/admin-payments/{id}/confirm', 'NewPaymentSystemController@confirm')->name('admin.payments.confirm');
    Route::post('/admin-payments/{id}/return', 'NewPaymentSystemController@returnPayment')->name('admin.payments.return');
    Route::get('/admin-payments/{id}/journey', 'NewPaymentSystemController@journey')->name('admin.payments.journey');

    // Agent Payment System (permission 165 or role=1)
    Route::get('/my-payments', 'AgentPaymentController@index')->name('agent.payments.index');
    Route::get('/my-payments/create', 'AgentPaymentController@create')->name('agent.payments.create');
    Route::post('/my-payments', 'AgentPaymentController@store')->name('agent.payments.store');
    Route::get('/my-payments/{id}/edit', 'AgentPaymentController@edit')->name('agent.payments.edit');
    Route::post('/my-payments/{id}', 'AgentPaymentController@update')->name('agent.payments.update');
    Route::get('/my-payments/{id}/journey', 'AgentPaymentController@journey')->name('agent.payments.journey');
    Route::get('/my-payments/fetch-order', 'AgentPaymentController@fetchOrderInfo')->name('agent.payments.fetch_order');
});

// ============================================================
// HR Portal SSO Redirect — logged-in agent → HR portal via bridge
// ============================================================
Route::middleware(['auth'])->get('/hr-portal/redirect', 'HrPortalRedirectController@redirect')
    ->name('hr.portal.redirect');

// ============================================================
// Washington Bridge Endpoints — key-authenticated, no session
// Called by washinton_hr to pull commission data and user status
// ============================================================
Route::post('/bridge/washington/agent/commission', 'Bridge\WashingtonBridgeController@commission')
    ->name('bridge.washington.commission');

Route::post('/bridge/washington/agent/status', 'Bridge\WashingtonBridgeController@status')
    ->name('bridge.washington.status');

// ============================================================
// External Bridge Auth (crazyrayssolutions → washinton_agent)
// Replaces old daydispatchagent bridge endpoints.
// X-Bridge-Key must match WASHINGTON_BRIDGE_SHARED_KEY in .env
// ============================================================
Route::prefix('bridge')->group(function () {
    Route::post('/register', 'Bridge\BridgeAuthController@register');
    Route::post('/login',    'Bridge\BridgeAuthController@login');
    Route::get('/sso/consume', 'Bridge\BridgeAuthController@consume')->name('bridge.sso.consume');
});

// ============================================================
// Washington Chat System
// Customer-facing iframe (public — no auth)
// Admin panel endpoints (auth required, role=1)
// ============================================================

// Public — customer chat widget
Route::get('/chat-widget', 'WashingtonChatController@indexIframe')->name('chat.widget');
Route::post('/chat/send', 'WashingtonChatController@chat')->name('chat.send');
Route::get('/chat/history', 'WashingtonChatController@chatHistory')->name('chat.history');
Route::post('/chat/update-read', 'WashingtonChatController@chatUpdateRead')->name('chat.update.read');

// Admin only — chat management
Route::middleware(['auth'])->group(function () {
    Route::get('/chat/show-history', 'WashingtonChatController@showHistory')->name('chat.show.history');
    Route::get('/chat/unread-count', 'WashingtonChatController@unreadCount')->name('chat.unread.count');
});
