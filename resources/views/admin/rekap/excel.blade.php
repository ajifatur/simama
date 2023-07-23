@if($data['all'] == false)
<table border="1" style="width: 100%">
    <thead>
        <tr>
            <th align="center" width="5"><b>No</b></th>
            <th align="center" width="50"><b>Nama</b></th>
            <th align="center" width="15"><b>Status</b></th>
            <th align="center" width="15"><b>Unit</b></th>
            <th align="center" width="50"><b>Alamat</b></th>
            <th align="center" width="15"><b>Kota</b></th>
            <th align="center" colspan="2"><b>Tanda Tangan</b></th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach($data['purnakarya'] as $p)
            @if($p->alamat()->latest('created_at')->first()->alamat_diketahui == 1)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $p->nama }}</td>
                <td>Purnakarya</td>
                <td>{{ $p->unit->nama }}</td>
                <td>{{ $p->alamat()->latest('created_at')->first()->alamat }}</td>
                <td>{{ $p->alamat()->latest('created_at')->first()->kota }}</td>
                @if($i % 2 == 1)
                    <td width="20" align="left">{{ $i }}.</td>
                    <td width="20" align="left"></td>
                @else
                    <td width="20" align="left"></td>
                    <td width="20" align="left">{{ $i }}.</td>
                @endif
            </tr>
            @php $i++ @endphp
            @endif
        @endforeach
        @foreach($data['warakawuri'] as $w)
            @if($w->purnakarya->alamat()->latest('created_at')->first()->alamat_diketahui == 1)
            <tr>
                <td>{{ $i }}</td>
                <td>{{ $w->nama }}</td>
                <td>Warakawuri</td>
                <td>{{ $w->purnakarya->unit->nama }}</td>
                <td>{{ $w->purnakarya->alamat()->latest('created_at')->first()->alamat }}</td>
                <td>{{ $w->purnakarya->alamat()->latest('created_at')->first()->kota }}</td>
                @if($i % 2 == 1)
                    <td width="20" align="left">{{ $i }}.</td>
                    <td width="20" align="left"></td>
                @else
                    <td width="20" align="left"></td>
                    <td width="20" align="left">{{ $i }}.</td>
                @endif
            </tr>
            @php $i++ @endphp
            @endif
        @endforeach
    </tbody>
</table>
@else
<table border="1" style="width: 100%">
    <thead>
        <tr>
            <th align="center" width="5"><b>No</b></th>
            <th align="center" width="50"><b>Nama</b></th>
            <th align="center" width="15"><b>Status</b></th>
            <th align="center" width="10"><b>Gender</b></th>
            <th align="center" width="15"><b>TMT Pensiun</b></th>
            <th align="center" width="15"><b>Unit</b></th>
            <th align="center" width="50"><b>Alamat</b></th>
            <th align="center" width="15"><b>Kota</b></th>
            <th align="center" colspan="2"><b>Tanda Tangan</b></th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
		@foreach($data['unit'] as $u)
			<?php
			$purnakarya = \App\Models\Purnakarya::where('unit_id','=',$u->id)->where('status','1')->orderBy('nama','asc')->get();
			$warakawuri = \App\Models\Warakawuri::whereHas('purnakarya', function (\Illuminate\Database\Eloquent\Builder $query) use ($u) {
				return $query->where('unit_id','=',$u->id);
			})->where('status','1')->orderBy('nama','asc')->get();
			?>
            @if(Request::query('status') == null || Request::query('status') == 1)
                @foreach($purnakarya as $p)
                    @if($p->alamat()->latest('created_at')->first()->alamat_diketahui == 1)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $p->nama }}</td>
                        <td>Purnakarya</td>
                        <td>{{ $p->gender }}</td>
                        <td>{{ $p->tmt_pensiun != null ? "'".date('d/m/Y', strtotime($p->tmt_pensiun)) : '-' }}</td>
                        <td>{{ $p->unit->nama }}</td>
                        <td>{{ $p->alamat()->latest('created_at')->first()->alamat }}</td>
                        <td>{{ $p->alamat()->latest('created_at')->first()->kota }}</td>
                        @if($i % 2 == 1)
                            <td width="20" align="left">{{ $i }}.</td>
                            <td width="20" align="left"></td>
                        @else
                            <td width="20" align="left"></td>
                            <td width="20" align="left">{{ $i }}.</td>
                        @endif
                    </tr>
                    @php $i++ @endphp
                    @endif
                @endforeach
            @endif
            @if(Request::query('status') == null || Request::query('status') == 2)
                @foreach($warakawuri as $w)
                    @if($w->purnakarya->alamat()->latest('created_at')->first()->alamat_diketahui == 1)
                    <tr>
                        <td>{{ $i }}</td>
                        <td>{{ $w->nama }}</td>
                        <td>Warakawuri</td>
                        <td>{{ $w->purnakarya->gender == 'L' || $w->purnakarya->gender == 'P' ? $w->purnakarya->gender == 'L' ? 'P' : 'L' : 'N' }}</td>
                        <td>-</td>
                        <td>{{ $w->purnakarya->unit->nama }}</td>
                        <td>{{ $w->purnakarya->alamat()->latest('created_at')->first()->alamat }}</td>
                        <td>{{ $w->purnakarya->alamat()->latest('created_at')->first()->kota }}</td>
                        @if($i % 2 == 1)
                            <td width="20" align="left">{{ $i }}.</td>
                            <td width="20" align="left"></td>
                        @else
                            <td width="20" align="left"></td>
                            <td width="20" align="left">{{ $i }}.</td>
                        @endif
                    </tr>
                    @php $i++ @endphp
                    @endif
                @endforeach
            @endif
		@endforeach
    </tbody>
</table>
@endif