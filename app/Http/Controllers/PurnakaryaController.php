<?php

namespace App\Http\Controllers;

use Auth;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Ajifatur\Helpers\DateTimeExt;
use App\Models\Purnakarya;
use App\Models\Unit;
use App\Models\Alamat;
use App\Models\Warakawuri;
use App\Imports\PurnakaryaImport;

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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function address($id)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);

        // Purnakarya
        $purnakarya = Purnakarya::findOrFail($id);

        // View
        return view('admin/purnakarya/address', [
            'purnakarya' => $purnakarya
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateAddress(Request $request)
    {
        // Validation
        $validator = Validator::make($request->all(), [
            // 'tanggal' => 'required',
        ]);
        
        // Check errors
        if($validator->fails()) {
            // Back to form page with validation error messages
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        else {
            // Purnakarya
            $purnakarya = Purnakarya::find($request->purnakarya_id);

            foreach($request->get('id') as $key=>$id) {
                // Simpan alamat purnakarya
                $alamat = Alamat::find($request->id[$key]);
                if(!$alamat) $alamat = new Alamat;
                $alamat->id = $request->id[$key];
                $alamat->purnakarya_id = $purnakarya->id;
                $alamat->alamat_diketahui = $request->alamat_diketahui[$key];
                $alamat->alamat = $request->alamat[$key];
                $alamat->kota = $request->kota[$key];
                $alamat->save();
            }

            // Redirect
            return redirect()->route('admin.purnakarya.address', ['id' => $purnakarya->id])->with(['message' => 'Berhasil mengupdate data.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function deleteAddress(Request $request)
    {
        // Check the access
        // has_access(__METHOD__, Auth::user()->role_id);
        
        // Alamat
        $alamat = Alamat::find($request->id);

        // Menghapus alamat
        $alamat->delete();
        
        // Redirect
        return redirect()->back()->with(['message' => 'Berhasil menghapus data.']);
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

    /**
     * Import.
     *
     * @return void
     */
    public function import()
    {
        $array = Excel::toArray(new PurnakaryaImport, public_path('spreadsheets/Purnakarya.xlsx'));

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
                $purnakarya->tmt_pensiun = $data[4] != '' ? $data[4] : null;
                $purnakarya->status = 1;
                $purnakarya->tanggal_md = null;
                $purnakarya->save();
    
                // Simpan alamat
                $alamat = new Alamat;
                $alamat->purnakarya_id = $purnakarya->id;
                $alamat->alamat = $data[5];
                $alamat->kota = $data[6];
                $alamat->tanggal_pindah = null;
                $alamat->save();
            }
        }
    }
}
