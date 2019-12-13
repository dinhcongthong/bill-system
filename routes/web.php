<?php

Route::get('login', 'HomeController@get_login')->name('login');
Route::post('post-login', 'HomeController@login')->name('post_login_route');
Route::post('logout', 'HomeController@logout')->name('post_logout_route');

Route::group(['middleware' => ['auth.editor']], function () {
    // home page
    Route::get('search', 'HomeController@home_search');
    Route::get('client-search/{id}', 'HomeController@client_search')->name('get_client_search_route');
    
    Route::prefix('sidebar')->middleware(['auth.setting'])->group(function () {
        Route::get('menu-setting', 'SettingController@index')->name('get_menu_setting_route');
        // info settting
        Route::get('info-setting', 'SettingController@info_setting')->name('get_info_setting_route');
        Route::get('get-new-setting/{id}', 'SettingController@getNew')->name('get_new_info_setting_route');
        Route::post('clone-new-setting/{id}', 'SettingController@postCloneNew')->name('post_clone_new_setting_route');
        Route::post('post-new-setting', 'SettingController@postNew')->name('post_new_info_setting_route');
        Route::post('delete-setting/{id}', 'SettingController@deleteInfo')->name('post_delete_info_setting_route');
        // store type
        Route::get('store-type', 'SettingController@storeTypeIndex')->name('get_store_type_index_route');
        Route::get('get-save-store-type/{id}', 'SettingController@storeTypeGetSave')->name('get_save_store_type_setting_route');
        Route::post('post-save-store-type', 'SettingController@storeTypePostSave')->name('post_save_store_type_setting_route');
        Route::post('delete-store-type/{id}', 'SettingController@storeTypeDelete')->name('post_delete_store_type_setting_route');
    });
    
    
    Route::prefix('client')->group(function () {
        Route::get('/', 'ClientController@index')->name('get_client_index_route');
        Route::post('baner/{id}', 'ClientController@postBaner')->name('post_baner_client_route');
        Route::post('remove-the-ban/{id}', 'ClientController@postRemoveTheBan')->name('post_remove_the_ban_client_route');
        Route::post('client-save', 'ClientController@postSaveClient');

        Route::post('delete/{id}', 'ClientController@delete')->name('post_delete_client_route');
        Route::get('edit/{id}', 'ClientController@getNew')->name('get_edit_client_route');
        Route::post('post-new', 'ClientController@postNew')->name('post_new_client_route');
    });
    
    Route::prefix('contract')->group(function () {
        Route::get('/', 'ContractController@index')->name('get_contract_index_route');
        Route::get('add-new/{client_id}', 'ContractController@getNew')->name('get_new_contract_route');
        Route::get('edit/{contract_id}/{client_name}', 'ContractController@getUpdate')->name("get_edit_contract_route");
        Route::post('post-new', 'ContractController@postNew')->name('post_new_contract_route');
        Route::post('scan-file-delete', 'ContractController@deleteScanFile');
        // Route::post('delete/{id}', 'ContractController@deleteContract');
        Route::post('delete', 'ContractController@deleteContract');
    });

    Route::get('update-payment-status-invoice/{id}/{user}', 'InvoiceController@updatePaymentStatus')->name('update_payment_status_invoice_route');
    Route::get('update-exported-invoice/{id}/{user}', 'InvoiceController@updateExported')->name('update_exported_invoice_route');

    Route::prefix('service')->group(function () {
        Route::get('/', 'ServiceController@index')->name('get_service_index_route');
        Route::post('delete/{id}', 'ServiceController@delete')->name('post_delete_service_route');
        Route::get('get-new/{service_id}', 'ServiceController@getNew')->name('get_new_service_route');
        Route::post('post-new/{service_id}', 'ServiceController@postNew')->name('post_new_service_route');
    });
    
    Route::prefix('pdf')->group(function() {
        Route::get('/', 'PdfController@index')->name('get_home');
        Route::get('/input', 'PdfController@input')->name('get_view_input');
        // Route::get('/view/{value}/{row}', 'PdfController@view')->name('get_preview');
        Route::get('/view/{data}', 'PdfController@view')->name('get_preview');
        Route::get('/confirm/{data}', 'PdfController@confirm')->name('confirm_preview');
        Route::get('/download/{value}', 'PdfController@download')->name('invoice_download');
        Route::get('/generate_download_page/{value}', 'PdfController@generate_download_page')->name('preview_invoice_download');
    });
    
    Route::prefix('activity')->group(function() {
        Route::get('/', 'ActivityController@index')->name('get_log_index_route');
    });
    
    // Route::get('/{segment}/{country?}', 'HomeController@getArea')->name('get_area_client_route');
    Route::get('{type?}/{country?}', 'HomeController@index')->name('home');
});
