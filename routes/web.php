<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('paybox.uri'))->group(function () {

    Route::get('/', 'DashboardController@index')
        ->name('paybox.dashboard');

    //Plan
    Route::get('/plans', 'PlansController@index')->name('plan.index');
    Route::get('/plan/create', 'PlansController@create')
        ->name('plan.create');
    Route::post('/plan/store', 'PlansController@store')
        ->name('plan.store');

    //Plan Subscriptons
    Route::get('/plan/{slug}/subscriptions', 'PlanSubscriptionsController@index')
        ->name('plan.subscriptions');

    //Subscriptions
    Route::get('/subscriptions', 'SubscriptionsController@index')
    ->name('subscriptions.index');
    Route::get('/subscriptions/{id}/show', 'SubscriptionsController@show')
    ->name('subscriptions.show');

    //Erors
    Route::get('/errors', 'ErrorsController@index')->name('errors.index');

    //creating a new subscription
    Route::post('/user-subscription/store', 'UserSubscriptionsController@store');
});

Route::group([
    'prefix' => 'ajax',
    'namespace' => 'Ajax',
], function () {

    //Payment info
    Route::post('/payment-info/update', 'PaymentInfoController@update');
    Route::get('/payment-info/show', 'PaymentInfoController@show');

    //User Subscriptions
    Route::get('/user-subscription/{planName}/show', 'UserSubscriptionsController@show');
    Route::post('/user-subscription/store', 'UserSubscriptionsController@store');
    Route::patch('/user-subscription/{plan}/update', 'UserSubscriptionsController@update');

    //Cancelled subscriptions
    Route::post('/cancelled-subscription/store', 'CancelledUserSubscriptionsController@store');
    Route::get('/cancelled-subscription/show', 'CancelledUserSubscriptionsController@show');
    Route::delete('/cancelled-subscription/destroy', 'CancelledUserSubscriptionsController@destroy');

    //Create a sub from resumeandswap
    Route::post('/subscription/store', 'SubscriptionsController@store');

    //Plan
    Route::delete('/plan/{slug}/delete', 'PlansController@destroy');
    Route::get('/plans', 'PlansController@index');

    //Errors
    Route::delete('/error/{slug}/delete', 'ErrorsController@destroy');

    //Invoices
    Route::get('/invoice/{id}/show', 'InvoicesController@show');
    //get authenticated user's invoices
    Route::get('/invoices/show', 'UserInvoicesController@show');
});