@extends('faturhelper::layouts/admin/main')

@section('title', 'Tambah Purnakarya')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Tambah Purnakarya</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.purnakarya.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="nama" class="form-control form-control-sm {{ $errors->has('nama') ? 'border-danger' : '' }}" value="{{ old('nama') }}" autofocus>
                            @if($errors->has('nama'))
                            <div class="small text-danger">{{ $errors->first('nama') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Jenis Kelamin <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender-L" value="L" {{ old('gender') == 'L' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender-L">
                                    Laki-Laki
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="gender" id="gender-P" value="P" {{ old('gender') == 'P' ? 'checked' : '' }}>
                                <label class="form-check-label" for="gender-P">
                                    Perempuan
                                </label>
                            </div>
                            @if($errors->has('gender'))
                            <div class="small text-danger">{{ $errors->first('gender') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Alamat <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <textarea name="alamat" class="form-control form-control-sm {{ $errors->has('alamat') ? 'border-danger' : '' }}" rows="3">{{ old('alamat') }}</textarea>
                            @if($errors->has('alamat'))
                            <div class="small text-danger">{{ $errors->first('alamat') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Kabupaten/Kota <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="kota" class="form-control form-control-sm {{ $errors->has('kota') ? 'border-danger' : '' }}" value="{{ old('kota') }}">
                            @if($errors->has('kota'))
                            <div class="small text-danger">{{ $errors->first('kota') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">No. Telepon</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="no_telepon" class="form-control form-control-sm {{ $errors->has('no_telepon') ? 'border-danger' : '' }}" value="{{ old('no_telepon') }}">
                            @if($errors->has('no_telepon'))
                            <div class="small text-danger">{{ $errors->first('no_telepon') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Unit <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <select name="unit" class="form-select form-select-sm {{ $errors->has('unit') ? 'border-danger' : '' }}" data-url="{{ route('api.unit.store') }}" data-token="{{ csrf_token() }}">
                                <option value="" disabled selected>--Pilih--</option>
                                @foreach($unit as $u)
                                <option value="{{ $u->id }}" {{ $u->id == old('unit') ? 'selected' : '' }}>{{ $u->nama }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('unit'))
                            <div class="small text-danger">{{ $errors->first('unit') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label"><span id="label-tanggal">TMT Pensiun</span> <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="tanggal" class="form-control form-control-sm {{ $errors->has('tanggal') ? 'border-danger' : '' }}" value="{{ old('tanggal') }}" autocomplete="off" placeholder="dd/mm/yyyy">
                                <span class="input-group-text"><i class="bi-calendar2"></i></span>
                            </div>
                            @if($errors->has('tanggal'))
                            <div class="small text-danger">{{ $errors->first('tanggal') }}</div>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-lg-2 col-md-3"></div>
                        <div class="col-lg-10 col-md-9">
                            <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
	</div>
</div>

@endsection

@section('js')

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    Spandiv.DatePicker("input[name=tanggal]");
    Spandiv.Select2("select[name='unit']", {
        enableAddOption: true
    });
</script>

@endsection

@section('css')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">

@endsection