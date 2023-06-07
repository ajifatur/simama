<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Warakawuri;
use App\Models\Purnakarya;
use App\Models\Unit;
use App\Models\Alamat;
use App\Imports\WarakawuriImport;

class WarakawuriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function active(Request $request)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Warakawuri
        $warakawuri = Warakawuri::has('purnakarya')->where('status','1')->orderBy('created_at','desc')->get();

        // View
        return view('admin/warakawuri/active', [
            'warakawuri' => $warakawuri
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function inactive(Request $request)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Warakawuri
        $warakawuri = Warakawuri::has('purnakarya')->where('status','0')->orderBy('tanggal_md','desc')->get();

        // View
        return view('admin/warakawuri/inactive', [
            'warakawuri' => $warakawuri
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Unit
        $unit = Unit::orderBy('num_order','asc')->get();

        // View
        return view('admin/warakawuri/create', [
            'unit' => $unit
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:200',
            'gender' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'unit' => 'required',
            'tanggal' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Simpan purnakarya
            $purnakarya = new Purnakarya;
            $purnakarya->unit_id = $request->unit;
            $purnakarya->nama = $request->nama;
            $purnakarya->gender = $request->gender;
            $purnakarya->no_telepon = $request->no_telepon;
            $purnakarya->tmt_pensiun = null;
            $purnakarya->status = 0;
            $purnakarya->tanggal_md = DateTimeExt::change($request->tanggal);
            $purnakarya->save();

            // Simpan alamat
            $alamat = new Alamat;
            $alamat->purnakarya_id = $purnakarya->id;
            $alamat->alamat = $request->alamat;
            $alamat->kota = $request->kota;
            $alamat->tanggal_pindah = null;
            $alamat->save();

            // Simpan warakawuri
            $warakawuri = new Warakawuri;
            $warakawuri->purnakarya_id = $purnakarya->id;
            $warakawuri->nama = $request->nama_warakawuri;
            $warakawuri->status = 1;
            $warakawuri->tanggal_md = null;
            $warakawuri->save();

            // Redirect
            return redirect()->route('admin.warakawuri.active')->with(['message' => 'Berhasil menambah data.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Warakawuri
        $warakawuri = Warakawuri::findOrFail($id);

        // View
        return view('admin/warakawuri/edit', [
            'warakawuri' => $warakawuri
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function inactivate($id)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Warakawuri
        $warakawuri = Warakawuri::findOrFail($id);

        // View
        return view('admin/warakawuri/inactivate', [
            'warakawuri' => $warakawuri
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toInactivate(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Simpan warakawuri
            $warakawuri = Warakawuri::find($request->id);
            $warakawuri->status = 0;
            $warakawuri->tanggal_md = DateTimeExt::change($request->tanggal);
            $warakawuri->save();

            // Redirect
            return redirect()->route('admin.warakawuri.inactive')->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);
        
        // Purnakarya
        $purnakarya = Purnakarya::find($request->id);

        // Menghapus purnakarya
        $purnakarya->delete();

        // Menghapus alamat
        $alamat = Alamat::where('purnakarya_id','=',$purnakarya->id)->delete();

        // Redirect
        if($purnakarya->status == 1)
            return redirect()->route('admin.purnakarya.active')->with(['message' => 'Berhasil menghapus data.']);
        elseif($purnakarya->status == 0)
            return redirect()->route('admin.purnakarya.inactive')->with(['message' => 'Berhasil menghapus data.']);
    }

    /**
     * Import.
     *
     * @return void
     */
    public function import()
    {
        $array = Excel::toArray(new WarakawuriImport, public_path('spreadsheets/Warakawuri.xlsx'));

        if(count($array)>0) {
            foreach($array[0] as $data) {
                // Unit
                $unit = Unit::where('nama','=',$data[1])->first();

                // Simpan purnakarya
                $purnakarya = new Purnakarya;
                $purnakarya->unit_id = $unit ? $unit->id : 0;
                $purnakarya->nama = $data[0];
                $purnakarya->gender = $data[2];
                $purnakarya->no_telepon = $data[3];
                $purnakarya->tmt_pensiun = null;
                $purnakarya->status = 0;
                $purnakarya->tanggal_md = $data[4] != '' ? $data[4] : null;
                $purnakarya->save();
    
                // Simpan alamat
                $alamat = new Alamat;
                $alamat->purnakarya_id = $purnakarya->id;
                $alamat->alamat = $data[5];
                $alamat->kota = $data[6];
                $alamat->tanggal_pindah = null;
                $alamat->save();
    
                // Simpan warakawuri
                $warakawuri = new Warakawuri;
                $warakawuri->purnakarya_id = $purnakarya->id;
                $warakawuri->nama = $data[7];
                $warakawuri->status = 1;
                $warakawuri->tanggal_md = null;
                $warakawuri->save();
            }
        }
    }
}
