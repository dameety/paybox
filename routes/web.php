<?php

use Illuminate\Support\Facades\Route;

Route::prefix(config('paybox.uri'))->group(function () {
    Route::get('/', 'DashboardController@index')->name('paybox.dashboard');

    Route::get('/plans', 'PlansController@index')->name('plan.index');
    Route::get('/plan/create', 'PlansController@create')->name('plan.create');
    Route::post('/plan/store', 'PlansController@store')->name('plan.store');

    Route::get('/plan/{slug}/subscriptions', 'PlanSubscriptionsController@index')->name('plan.subscriptions');

    Route::get('/subscriptions', 'SubscriptionsController@index')->name('subscriptions.index');
    Route::get('/subscriptions/{id}/show', 'SubscriptionsController@show')->name('subscriptions.show');

    Route::get('/errors', 'ErrorsController@index')->name('errors.index');

    Route::post('/user-subscription/store', 'UserSubscriptionsController@store');
});

Route::group([
    'prefix' => 'ajax',
    'namespace' => 'Ajax',
], function () {

    Route::post('/payment-info/update', 'PaymentInfoController@update');
    Route::get('/payment-info/show', 'PaymentInfoController@show');

    Route::get('/user-subscription/{planName}/show', 'UserSubscriptionsController@show');
    Route::post('/user-subscription/store', 'UserSubscriptionsController@store');
    Route::patch('/user-subscription/{plan}/update', 'UserSubscriptionsController@update');

    Route::post('/cancelled-subscription/store', 'CancelledUserSubscriptionsController@store');
    Route::get('/cancelled-subscription/show', 'CancelledUserSubscriptionsController@show');
    Route::delete('/cancelled-subscription/destroy', 'CancelledUserSubscriptionsController@destroy');

    Route::post('/subscription/store', 'SubscriptionsController@store');

    Route::delete('/plan/{slug}/delete', 'PlansController@destroy');
    Route::get('/plans', 'PlansController@index');

    Route::delete('/error/{slug}/delete', 'ErrorsController@destroy');

    Route::get('/invoice/{id}/show', 'InvoicesController@show');

    Route::get('/invoices/show', 'UserInvoicesController@show');
});