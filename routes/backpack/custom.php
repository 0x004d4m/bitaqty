<?php

use App\Http\Controllers\Admin\NotificationCrudController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix'     => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace'  => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('type', 'TypeCrudController');
    Route::crud('country', 'CountryCrudController');
    Route::crud('currency', 'CurrencyCrudController');
    Route::crud('state', 'StateCrudController');
    Route::get('States', 'StateCrudController@states');
    Route::crud('category', 'CategoryCrudController');
    Route::get('Categories', 'CategoryCrudController@categories');
    Route::crud('subcategory', 'SubcategoryCrudController');
    Route::get('Subcategories', 'SubcategoryCrudController@subcategories');
    Route::crud('field-type', 'FieldTypeCrudController');
    Route::crud('field', 'FieldCrudController');
    Route::crud('subcategory-field', 'SubcategoryFieldCrudController');
    Route::crud('product', 'ProductCrudController');
    Route::crud('prepaid-card-stock', 'PrepaidCardStockCrudController');
    Route::crud('group', 'GroupCrudController');
    Route::crud('group-price', 'GroupPriceCrudController');
    Route::crud('dashboard-value', 'DashboardValueCrudController');
    Route::crud('onboarding', 'OnboardingCrudController');
    Route::crud('news', 'NewsCrudController');
    Route::crud('term', 'TermCrudController');
    Route::crud('maintenance', 'MaintenanceCrudController');
    Route::get('maintenance/on', 'MaintenanceCrudController@on');
    Route::get('maintenance/off', 'MaintenanceCrudController@off');
    Route::crud('credit-status', 'CreditStatusCrudController');
    Route::crud('supported-account', 'SupportedAccountCrudController');
    Route::crud('credit-type', 'CreditTypeCrudController');
    Route::crud('credit', 'CreditCrudController');
    Route::crud('credit-card', 'CreditCardCrudController');
    Route::crud('vendor', 'VendorCrudController');
    Route::crud('client', 'ClientCrudController');
    Route::crud('issue-type', 'IssueTypeCrudController');
    Route::crud('issue', 'IssueCrudController');
    Route::crud('notification', 'NotificationCrudController');
    Route::get('notification/{id}/send', [NotificationCrudController::class, 'send']);
    Route::crud('order-status', 'OrderStatusCrudController');
    Route::crud('order', 'OrderCrudController');
    Route::crud('order-prepaid-card-stock', 'OrderPrepaidCardStockCrudController');
    Route::get('Users', 'ClientCrudController@users');
}); // this should be the absolute last line of this file
