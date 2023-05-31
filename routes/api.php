<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/unit', function() {
    $unit = \App\Models\Unit::orderBy('num_order','asc')->get();
    return response()->json($unit, 200);
})->name('api.unit');

Route::post('/unit/store', function(Request $request) {
    // Unit terakhir
    $latest_unit = \App\Models\Unit::orderBy('num_order','desc')->first();

    // Simpan unit
    $unit = new \App\Models\Unit;
    $unit->nama = $request->keyword;
    $unit->num_order = $latest_unit ? $latest_unit->num_order + 1 : 1;
    $unit->save();

    // JSON
    return response()->json([
        'id' => $unit->id,
        'keyword' => $unit->nama
    ]);
})->name('api.unit.store');

\Ajifatur\Helpers\RouteExt::api();