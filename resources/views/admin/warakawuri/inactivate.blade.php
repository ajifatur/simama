@extends('faturhelper::layouts/admin/main')

@section('title', 'Nonaktifkan Warakawuri')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Nonaktifkan Warakawuri</h1>
</div>
<div class="row">
	<div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="{{ route('admin.warakawuri.to-inactivate') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $warakawuri->id }}">
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Nama</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" class="form-control form-control-sm" value="{{ $warakawuri->nama }}" disabled>
                        </div>
                    </div>
                    <div class="row mb-3">
                        <label class="col-lg-2 col-md-3 col-form-label">Unit</label>
                        <div class="col-lg-10 col-md-9">
                            <input type="text" class="form-control form-control-sm" value="{{ $warakawuri->purnakarya->unit->nama }}" disabled>
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
</script>

@endsection