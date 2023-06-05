<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Purnakarya;
use App\Models\Unit;
use App\Models\Alamat;
use App\Models\Warakawuri;

class PurnakaryaController extends Controller
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

        // Purnakarya
        $purnakarya = Purnakarya::where('status','1')->orderBy('tmt_pensiun','desc')->get();

        // View
        return view('admin/purnakarya/active', [
            'purnakarya' => $purnakarya
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

        // Purnakarya
        $purnakarya = Purnakarya::where('status','0')->orderBy('tanggal_md','desc')->get();

        // View
        return view('admin/purnakarya/inactive', [
            'purnakarya' => $purnakarya
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
        return view('admin/purnakarya/create', [
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
            $purnakarya->tmt_pensiun = DateTimeExt::change($request->tanggal);
            $purnakarya->status = 1;
            $purnakarya->tanggal_md = null;
            $purnakarya->save();

            // Simpan alamat
            $alamat = new Alamat;
            $alamat->purnakarya_id = $purnakarya->id;
            $alamat->alamat = $request->alamat;
            $alamat->kota = $request->kota;
            $alamat->tanggal_pindah = null;
            $alamat->save();

            // Redirect
            return redirect()->route('admin.purnakarya.active')->with(['message' => 'Berhasil menambah data.']);
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

        // Purnakarya
        $purnakarya = Purnakarya::findOrFail($id);

        // Unit
        $unit = Unit::orderBy('num_order','asc')->get();

        // View
        return view('admin/purnakarya/edit', [
            'purnakarya' => $purnakarya,
            'unit' => $unit
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            'nama' => 'required|max:200',
            'gender' => 'required',
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
            $purnakarya = Purnakarya::find($request->id);
            $purnakarya->unit_id = $request->unit;
            $purnakarya->nama = $request->nama;
            $purnakarya->gender = $request->gender;
            $purnakarya->no_telepon = $request->no_telepon;
            $purnakarya->tmt_pensiun = $purnakarya->status == 1 ? DateTimeExt::change($request->tanggal) : $purnakarya->tmt_pensiun;
            $purnakarya->tanggal_md = $purnakarya->status == 0 ? DateTimeExt::change($request->tanggal) : $purnakarya->tanggal_md;
            $purnakarya->save();

            // Jika status MD, maka mengubah nama warakawuri
            if($purnakarya->status == 0) {
                $warakawuri = Warakawuri::where('purnakarya_id','=',$purnakarya->id)->first();
                if($warakawuri) {
                    $warakawuri->nama = ($purnakarya->gender == 'L' ? 'Ibu' : 'Bapak').' '.$request->nama;
                    $warakawuri->save();
                }
            }

            // Redirect
            if($purnakarya->status == 1)
                return redirect()->route('admin.purnakarya.active')->with(['message' => 'Berhasil mengupdate data.']);
            elseif($purnakarya->status == 0)
                return redirect()->route('admin.purnakarya.inactive')->with(['message' => 'Berhasil mengupdate data.']);
        }
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

        // Purnakarya
        $purnakarya = Purnakarya::findOrFail($id);

        // View
        return view('admin/purnakarya/inactivate', [
            'purnakarya' => $purnakarya
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
            'warakawuri' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Simpan purnakarya
            $purnakarya = Purnakarya::find($request->id);
            $purnakarya->status = 0;
            $purnakarya->tanggal_md = DateTimeExt::change($request->tanggal);
            $purnakarya->save();

            // Jika ada warakawuri
            if($request->warakawuri == 1) {
                $warakawuri = new Warakawuri;
                $warakawuri->purnakarya_id = $purnakarya->id;
                $warakawuri->nama = $request->nama;
                $warakawuri->status = 1;
                $warakawuri->tanggal_md = null;
                $warakawuri->save();
            }

            // Redirect
            return redirect()->route('admin.purnakarya.inactive')->with(['message' => 'Berhasil mengupdate data.']);
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

        // Menghapus warakawuri
        $warakawuri = Warakawuri::where('purnakarya_id','=',$purnakarya->id)->delete();

        // Menghapus alamat
        $alamat = Alamat::where('purnakarya_id','=',$purnakarya->id)->delete();

        // Redirect
        if($purnakarya->status == 1)
            return redirect()->route('admin.purnakarya.active')->with(['message' => 'Berhasil menghapus data.']);
        elseif($purnakarya->status == 0)
            return redirect()->route('admin.purnakarya.inactive')->with(['message' => 'Berhasil menghapus data.']);
    }
}