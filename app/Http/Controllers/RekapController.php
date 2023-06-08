<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Purnakarya;
use App\Models\Unit;
use App\Models\Alamat;
use App\Models\Warakawuri;
use App\Exports\WredatamaExport;

class RekapController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Unit
        $unit = Unit::orderBy('num_order','asc')->get();

        // View
        return view('admin/rekap/index', [
            'unit' => $unit
        ]);
    }

    /**
     * Show the detail of the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail(Request $request, $id)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Unit
        $unit = Unit::findOrFail($id);

        // Purnakarya
        $purnakarya = Purnakarya::where('unit_id','=',$unit->id)->where('status','1')->orderBy('nama','asc')->get();

        // Warakawuri
        $warakawuri = Warakawuri::whereHas('purnakarya', function (Builder $query) use ($unit) {
            return $query->where('unit_id','=',$unit->id);
        })->where('status','1')->orderBy('nama','asc')->get();

        // View
        return view('admin/rekap/detail', [
            'unit' => $unit,
            'purnakarya' => $purnakarya,
            'warakawuri' => $warakawuri,
        ]);
    }
    
    /**
     * Export to Excel
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request, $id)
    {
		ini_set("memory_limit", "-1");

        // Unit
        $unit = Unit::findOrFail($id);
		
        // Purnakarya
        $purnakarya = Purnakarya::where('unit_id','=',$unit->id)->where('status','1')->orderBy('nama','asc')->get();

        // Warakawuri
        $warakawuri = Warakawuri::whereHas('purnakarya', function (Builder $query) use ($unit) {
            return $query->where('unit_id','=',$unit->id);
        })->where('status','1')->orderBy('nama','asc')->get();

        return Excel::download(new WredatamaExport([
			'all' => false,
            'unit' => $unit,
            'purnakarya' => $purnakarya,
            'warakawuri' => $warakawuri,
        ]), 'Data Wredatama Aktif '.$unit->nama.' '.date('Y').'.xlsx');
    }
    
    /**
     * Export to Excel (All)
     *
     * @return \Illuminate\Http\Response
     */
    public function exportAll(Request $request)
    {
		ini_set("memory_limit", "-1");
		
		// Unit
		$unit = Unit::orderBy('num_order','asc')->get();

        return Excel::download(new WredatamaExport([
			'all' => true,
            'unit' => $unit,
        ]), 'Data Wredatama Aktif '.date('Y').'.xlsx');
    }
}
