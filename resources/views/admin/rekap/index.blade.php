@extends('faturhelper::layouts/admin/main')

@section('title', 'Rekap Aktif')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Rekap Aktif</h1>
    <div class="btn-group">
        <a class="btn btn-sm btn-success dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi-download me-1"></i> Ekspor ke Excel</a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
            <li><a class="dropdown-item" href="{{ route('admin.rekap.export-all') }}" target="_blank">Semua Data</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.rekap.export-all', ['status' => 1]) }}" target="_blank">Purnakarya</a></li>
            <li><a class="dropdown-item" href="{{ route('admin.rekap.export-all', ['status' => 2]) }}" target="_blank">Warakawuri</a></li>
        </ul>
    </div>
</div>
<div class="row">
	<div class="col-12">
		<div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm table-hover table-bordered" id="datatable">
                        <thead class="bg-light">
                            <tr>
                                <th>Unit</th>
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($unit as $u)
                            <tr>
                                <td>{{ $u->nama }}</td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.rekap.export', ['id' => $u->id]) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Download Rekap" target="_blank"><i class="bi-download"></i></a>
                                        <!-- <a href="#" class="btn btn-sm btn-secondary" data-bs-toggle="tooltip" title="Daftar Hadir"><i class="bi-list"></i></a> -->
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

@endsection

@section('js')

<script type="text/javascript">
    // DataTable
    Spandiv.DataTable("#datatable");
</script>

@endsection

@section('css')

<style>
    #datatable tr td {vertical-align: top;}
</style>

@endsection