@extends('faturhelper::layouts/admin/main')

@section('title', 'Kelola Warakawuri Aktif')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola Warakawuri Aktif</h1>
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
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th width="30"><input type="checkbox" class="form-check-input checkbox-all"></th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th width="100">Unit</th>
                                <th width="100">Tanggal MD Pasangan</th>
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($warakawuri as $w)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td>{{ $w->nama }}</td>
                                <td>
                                    {{ $w->purnakarya->alamat()->first()->alamat }}, {{ $w->purnakarya->alamat()->first()->kota }}
                                </td>
                                <td>{{ $w->purnakarya->unit->nama }}</td>
                                <td>
                                    <span class="d-none">{{ $w->purnakarya->tanggal_md }}</span>
                                    {{ \Ajifatur\Helpers\DateTimeExt::change($w->purnakarya->tanggal_md) }}
                                </td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.warakawuri.inactivate', ['id' => $w->id]) }}" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Nonaktifkan"><i class="bi-x"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $w->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
		</div>
	</div>
</div>

<form class="form-delete d-none" method="post" action="{{ route('admin.warakawuri.delete') }}">
    @csrf
    <input type="hidden" name="id">
</form>

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable");

    // Button Delete
    Spandiv.ButtonDelete(".btn-delete", ".form-delete");
</script>

@endsection

@section('css')

<style>
    #datatable tr td {vertical-align: top;}
</style>

@endsection