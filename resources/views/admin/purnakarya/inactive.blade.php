@extends('faturhelper::layouts/admin/main')

@section('title', 'Kelola Purnakarya MD')

@section('content')

<div class="d-sm-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-2 mb-sm-0">Kelola Purnakarya MD</h1>
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
                                <th width="100">Tanggal MD</th>
                                <th width="60">Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($purnakarya as $p)
                            <tr>
                                <td align="center"><input type="checkbox" class="form-check-input checkbox-one"></td>
                                <td>{{ $p->nama }}</td>
                                <td>
                                    @if($p->alamat()->latest('created_at')->first()->alamat_diketahui == 1)
                                        {{ $p->alamat()->latest('created_at')->first()->alamat }}, {{ $p->alamat()->latest('created_at')->first()->kota }}
                                    @else
                                        <span class="text-danger">Alamat tidak diketahui</span>
                                    @endif
                                </td>
                                <td>{{ $p->unit->nama }}</td>
                                <td>
                                    <span class="d-none">{{ $p->tanggal_md }}</span>
                                    {{ \Ajifatur\Helpers\DateTimeExt::change($p->tanggal_md) }}
                                </td>
                                <td align="center">
                                    <div class="btn-group">
                                        <a href="{{ route('admin.purnakarya.edit', ['id' => $p->id]) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="Edit"><i class="bi-pencil"></i></a>
                                        <a href="{{ route('admin.purnakarya.address', ['id' => $p->id]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Alamat"><i class="bi-pin-map"></i></a>
                                        <a href="#" class="btn btn-sm btn-danger btn-delete" data-id="{{ $p->id }}" data-bs-toggle="tooltip" title="Hapus"><i class="bi-trash"></i></a>
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

<form class="form-delete d-none" method="post" action="{{ route('admin.purnakarya.delete') }}">
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