@extends('faturhelper::layouts/admin/main')

@section('title', 'Alamat Warakawuri')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Alamat Warakawuri: {{ $warakawuri->nama }}</h1>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-body">
                @if(Session::get('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <div class="alert-message">{{ Session::get('message') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif
                <form method="post" action="{{ route('admin.warakawuri.address.update') }}">
                    @csrf
                    <input type="hidden" name="warakawuri_id" value="{{ $warakawuri->id }}">
                    <p class="fw-bold">Keterangan: Yang ditandai warna kuning adalah alamat sekarang.</p>
                    <button type="button" class="btn btn-sm btn-info btn-add mb-3"><i class="bi-plus me-1"></i> Tambah Alamat</button>
                    <div class="table-responsive">
                        <table class="table table-sm table-hover table-bordered mb-0" id="table">
                            <thead class="bg-light">
                                <tr>
                                    <th width="100">Alamat Diketahui</th>
                                    <th>Alamat</th>
                                    <th width="200">Kota</th>
                                    <th width="60">Opsi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($warakawuri->purnakarya->alamat()->orderBy('created_at','desc')->get() as $key=>$a)
                                <tr class="{{ $key == 0 ? 'bg-warning' : '' }}">
                                    <input type="hidden" name="id[]" value="{{ $a->id }}">
                                    <td align="center">
                                        <select class="form-select form-select-sm" name="alamat_diketahui[]">
                                            <option value="1" {{ $a->alamat_diketahui == 1 ? 'selected' : '' }}>Ya</option>
                                            <option value="0" {{ $a->alamat_diketahui == 0 ? 'selected' : '' }}>Tidak</option>
                                        </select>
                                    </td>
                                    <td><textarea class="form-control form-control-sm" name="alamat[]" rows="3">{{ $a->alamat }}</textarea></td>
                                    <td><textarea class="form-control form-control-sm" name="kota[]" rows="3">{{ $a->kota }}</textarea></td>
                                    <td align="center">
                                        <div class="btn-group">
                                            <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $a->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-sm btn-primary"><i class="bi-save me-1"></i> Update Alamat</button>
                </form>
            </div>
		</div>
	</div>
</div>

<form class="form-delete d-none" method="post" action="{{ route('admin.warakawuri.address.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // Button Add
    $(document).on("click", ".btn-add", function(e) {
        e.preventDefault();
        if($("#table tbody #added-address").length == 0) {
            var html = '';
            html += '<tr id="added-address">';
            html += '<input type="hidden" name="id[]" value="0">';
            html += '<td align="center">';
            html += '<select class="form-select form-select-sm" name="alamat_diketahui[]">';
            html += '<option value="1">Ya</option>';
            html += '<option value="0">Tidak</option>';
            html += '</select>';
            html += '</td>';
            html += '<td><textarea class="form-control form-control-sm" name="alamat[]" rows="3"></textarea></td>';
            html += '<td><textarea class="form-control form-control-sm" name="kota[]" rows="3"></textarea></td>';
            html += '<td align="center">';
            html += '<div class="btn-group">';
            html += '-';
            html += '</div>';
            html += '</td>';
            html += '</tr>';
            $("#table tbody").prepend(html);
        }
    });

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
</script>

@endsection

@section('css')

<style>
    #table tr th {text-align: center; vertical-align: middle;}
    #table tr td {vertical-align: middle!important;}
</style>

@endsection