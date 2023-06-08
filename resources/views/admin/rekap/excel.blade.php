@if($data['all'] != true)
<table border="1" style="width: 100%">
    <thead>
        <tr>
            <th width="5">No</th>
            <th width="50">Nama</th>
            <th width="15">Status</th>
            <th width="15">Unit</th>
            <th colspan="2">Tanda Tangan</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach($data['purnakarya'] as $p)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $p->nama }}</td>
            <td>Purnakarya</td>
            <td>{{ $p->unit->nama }}</td>
            @if($i % 2 == 1)
                <td width="20" align="left">{{ $i }}.</td>
                <td width="20" align="left"></td>
            @else
                <td width="20" align="left"></td>
                <td width="20" align="left">{{ $i }}.</td>
            @endif
        </tr>
        @php $i++ @endphp
        @endforeach
        @foreach($data['warakawuri'] as $w)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $w->nama }}</td>
            <td>Warakawuri</td>
            <td>{{ $w->purnakarya->unit->nama }}</td>
            @if($i % 2 == 1)
                <td width="20" align="left">{{ $i }}.</td>
                <td width="20" align="left"></td>
            @else
                <td width="20" align="left"></td>
                <td width="20" align="left">{{ $i }}.</td>
            @endif
        </tr>
        @php $i++ @endphp
        @endforeach
    </tbody>
</table>
@else
<table border="1" style="width: 100%">
    <thead>
        <tr>
            <th width="5">No</th>
            <th width="50">Nama</th>
            <th width="15">Status</th>
            <th width="15">Unit</th>
            <th colspan="2">Tanda Tangan</th>
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
			@foreach($purnakarya as $p)
			<tr>
				<td>{{ $i }}</td>
				<td>{{ $p->nama }}</td>
				<td>Purnakarya</td>
				<td>{{ $p->unit->nama }}</td>
				@if($i % 2 == 1)
					<td width="20" align="left">{{ $i }}.</td>
					<td width="20" align="left"></td>
				@else
					<td width="20" align="left"></td>
					<td width="20" align="left">{{ $i }}.</td>
				@endif
			</tr>
			@php $i++ @endphp
			@endforeach
			@foreach($warakawuri as $w)
			<tr>
				<td>{{ $i }}</td>
				<td>{{ $w->nama }}</td>
				<td>Warakawuri</td>
				<td>{{ $w->purnakarya->unit->nama }}</td>
				@if($i % 2 == 1)
					<td width="20" align="left">{{ $i }}.</td>
					<td width="20" align="left"></td>
				@else
					<td width="20" align="left"></td>
					<td width="20" align="left">{{ $i }}.</td>
				@endif
			</tr>
			@php $i++ @endphp
			@endforeach
		@endforeach
    </tbody>
</table>
@endif
