<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('auth.login');
});

Route::group(['middleware' => ['faturhelper.admin']], function() {
    // Purnakarya
    Route::get('/admin/purnakarya/active', 'PurnakaryaController@active')->name('admin.purnakarya.active');
    Route::get('/admin/purnakarya/inactive', 'PurnakaryaController@inactive')->name('admin.purnakarya.inactive');
    Route::get('/admin/purnakarya/create', 'PurnakaryaController@create')->name('admin.purnakarya.create');
    Route::post('/admin/purnakarya/store', 'PurnakaryaController@store')->name('admin.purnakarya.store');
    Route::get('/admin/purnakarya/edit/{id}', 'PurnakaryaController@edit')->name('admin.purnakarya.edit');
    Route::post('/admin/purnakarya/update', 'PurnakaryaController@update')->name('admin.purnakarya.update');
    Route::get('/admin/purnakarya/inactivate/{id}', 'PurnakaryaController@inactivate')->name('admin.purnakarya.inactivate');
    Route::post('/admin/purnakarya/to-inactivate', 'PurnakaryaController@toInactivate')->name('admin.purnakarya.to-inactivate');
    Route::post('/admin/purnakarya/delete', 'PurnakaryaController@delete')->name('admin.purnakarya.delete');

    // Warakawuri
    Route::get('/admin/warakawuri/active', 'WarakawuriController@active')->name('admin.warakawuri.active');
    Route::get('/admin/warakawuri/inactive', 'WarakawuriController@inactive')->name('admin.warakawuri.inactive');
    Route::get('/admin/warakawuri/create', 'WarakawuriController@create')->name('admin.warakawuri.create');
    Route::post('/admin/warakawuri/store', 'WarakawuriController@store')->name('admin.warakawuri.store');
    Route::get('/admin/warakawuri/edit/{id}', 'WarakawuriController@edit')->name('admin.warakawuri.edit');
    Route::post('/admin/warakawuri/update', 'WarakawuriController@update')->name('admin.warakawuri.update');
    Route::get('/admin/warakawuri/inactivate/{id}', 'WarakawuriController@inactivate')->name('admin.warakawuri.inactivate');
    Route::post('/admin/warakawuri/to-inactivate', 'WarakawuriController@toInactivate')->name('admin.warakawuri.to-inactivate');
    Route::post('/admin/warakawuri/delete', 'WarakawuriController@delete')->name('admin.warakawuri.delete');

    // Rekap
    Route::get('/admin/rekap', 'RekapController@index')->name('admin.rekap.index');
    Route::get('/admin/rekap/detail/{id}', 'RekapController@detail')->name('admin.rekap.detail');
});

\Ajifatur\Helpers\RouteExt::auth();
\Ajifatur\Helpers\RouteExt::admin();