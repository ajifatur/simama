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

        // Purnakarya aktif
        $purnakarya_aktif = Purnakarya::where('unit_id','=',$unit->id)->where('tmt_pensiun','<=',date('y-m-d'))->orderBy('nama','asc')->get()->toArray();

        // Purnakarya MD
        $purnakarya_md = Purnakarya::where('unit_id','=',$unit->id)->where('tmt_pensiun','=',null)->where('tanggal_md','<',date('y-m-d'))->orderBy('nama','asc')->get()->toArray();

        // Purnakarya
        $purnakarya = array_merge($purnakarya_aktif, $purnakarya_md);
        $key_values = array_column($purnakarya, 'nama'); 
        array_multisort($key_values, SORT_ASC, $purnakarya);

        // View
        return view('admin/rekap/detail', [
            'unit' => $unit,
            'purnakarya' => $purnakarya,
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