@extends('faturhelper::layouts/admin/main')

@section('title', 'Nonaktifkan Purnakarya')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Nonaktifkan Purnakarya</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.purnakarya.to-inactivate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $purnakarya->id }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" class="form-control form-control-sm" value="{{ $purnakarya->nama }}" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Unit</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" class="form-control form-control-sm" value="{{ $purnakarya->unit->nama }}" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Tanggal Meninggal Dunia</label>
                        <div class="col-lg-10 col-md-9">
                            <div class="input-group input-group-sm">
                                <input type="text" name="tanggal" class="form-control form-control-sm {{ $errors->has('tanggal') ? 'border-danger' : '' }}" value="{{ old('tanggal') }}" autocomplete="off" placeholder="dd/mm/yyyy">
                                <span class="input-group-text"><i class="bi-calendar2"></i></span>
                            </div>
                            <div class="small text-muted">Kosongi saja jika tanggal tidak diketahui.</div>
                            @if($errors->has('tanggal'))
                            <div class="small text-danger">{{ $errors->first('tanggal') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Masih Ada Suami/Istri <span class="text-danger">*</span></label>
                        <div class="col-lg-10 col-md-9">
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="warakawuri" id="warakawuri-1" value="1" {{ old('warakawuri') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label" for="warakawuri-1">
                                    Ya
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="warakawuri" id="warakawuri-0" value="0" {{ old('warakawuri') == '0' ? 'checked' : '' }}>
                                <label class="form-check-label" for="warakawuri-0">
                                    Tidak
                                </label>
                            </div>
                            @if($errors->has('warakawuri'))
                            <div class="small text-danger">{{ $errors->first('warakawuri') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row mb-3 d-none" id="nama-warakawuri">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama Warakawuri</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" name="nama" class="form-control form-control-sm" value="{{ $purnakarya->gender == 'L' ? 'Ibu' : 'Bapak' }} {{ $purnakarya->nama }}" readonly>
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

<script>
    Spandiv.DatePicker("input[name=tanggal]");

    $(document).on("change", "input[name=warakawuri]", function() {
        $(this).val() == 1 ? $("#nama-warakawuri").removeClass("d-none") : $("#nama-warakawuri").addClass("d-none")
    });
</script>

@endsection