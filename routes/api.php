<?php

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

// Translations route.
Route::get('locales', 'LocaleController@index');

Route::middleware('auth:api')->group(function () {
    // User resource routes.
    Route::get('users/current', 'UserController@current')->name('users.current');
    Route::get('users/search', 'UserController@search')->name('users.search');
    Route::resource('users', 'UserController');

    // Project resource routes.
    Route::resource('projects', 'ProjectController');

    // Learning Products resource routes.
    Route::resource('learning-products', 'LearningProductController', [
        // Override default parameters formatting so that they use camelcase.
        'parameters' => ['learning-products' => 'learningProduct']
    ]);

    // Learning Product Codes resource routes.
    Route::resource('learning-product-codes', 'LearningProductCodeController', [
        // Override default parameters formatting so that they use camelcase.
        'parameters' => ['learning-product-codes' => 'learningProductCode']
    ]);

    // Organizational Unit routes.
    Route::get('organizational-units', 'OrganizationalUnitController@index')->name('organizational-units.index');
    Route::get('organizational-units/{organizationalUnit}/edit', 'OrganizationalUnitController@edit')->name('organizational-units.edit');
    Route::put('organizational-units/{organizationalUnit}', 'OrganizationalUnitController@update')->name('organizational-units.update');

    // Process definition routes.
    Route::get('process-definitions/{entityType}', 'ProcessDefinitionController@show')->name('process-definitions.show');
    Route::post('process-definitions/{processDefinition}', 'ProcessDefinitionController@startProcess')->name('process-definitions.start-process');

    // Process instance routes.
    Route::get('process-instances', 'ProcessInstanceController@index')->name('process-instances.index');
    Route::get('process-instances/{id}', 'ProcessInstanceController@show')->name('process-instances.show');
    Route::put('process-instances/{id}/cancel', 'ProcessInstanceController@cancel')->name('process-instances.cancel');

    // Process instance form routes.
    Route::get('process-instance-forms/{processInstanceFormData}', 'ProcessInstanceFormController@show')->name('process-instance-forms.show');
    Route::put('process-instance-forms/{processInstanceForm}/claim', 'ProcessInstanceFormController@claim')->name('process-instance-forms.claim');
    Route::put('process-instance-forms/{processInstanceForm}/unclaim', 'ProcessInstanceFormController@unclaim')->name('process-instance-forms.unclaim');
    Route::put('process-instance-forms/{processInstanceForm}/release', 'ProcessInstanceFormController@release')->name('process-instance-forms.release');
    Route::put('process-instance-forms/{processInstanceFormData}/edit', 'ProcessInstanceFormController@edit')->name('process-instance-forms.edit');
    Route::put('process-instance-forms/{processInstanceFormData}/submit', 'ProcessInstanceFormController@submit')->name('process-instance-forms.submit');

    // Process notification routes.
    Route::get('process-notifications', 'ProcessNotificationController@index')->name('process-notifications.index');
    Route::get('process-notifications/{processNotification}/edit', 'ProcessNotificationController@edit')->name('process-notifications.edit');
    Route::put('process-notifications/{processNotification}', 'ProcessNotificationController@update')->name('process-notifications.update');

    // List entities routes.
    Route::get('lists/{entityType}', 'ListController@show')->name('lists.show');
    Route::get('lists', 'ListController@showMultiple')->name('lists.show-multiple');

    // Search routes.
    Route::get('search/ldap', 'SearchController@ldap')->name('search.ldap');
    Route::get('search/{entityType}', 'SearchController@search')->name('search.default');

    // Information sheets routes.
    Route::get('information-sheets/{entityType}/{entityId}', 'InformationSheetController@show')->name('information-sheets.show');

    // Authorization routes.
    Route::get('authorizations/{entityType}/{entityId}', 'AuthorizationController@actions')->where('entityId', '[0-9]+')->name('authorizations.actions');
    Route::get('authorizations/{entityType}/{entityId}/{action}', 'AuthorizationController@actions')->where('entityId', '[0-9]+')->name('authorizations.action');
    Route::get('authorizations/{entityType}/{action}', 'AuthorizationController@entityAction')->name('authorizations.entity-action');
    Route::get('authorizations/{entityType}/{entityId}/start-process/{processDefinition}', 'AuthorizationController@startProcess')->name('authorization.start-process');

    // Contact form routes.
    Route::post('contact-form', 'ContactFormController@submit');
});
