<table border="1" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Status</th>
            <th>Unit</th>
            <th colspan="2">Tanda Tangan</th>
        </tr>
    </thead>
    <tbody>
        @php $i = 1; @endphp
        @foreach($purnakarya as $p)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $p->nama }}</td>
            <td>Purnakarya</td>
            <td>{{ $unit->nama }}</td>
            @if($i % 2 == 1)
                <td width="200">{{ $i }}.</td>
                <td width="200"></td>
            @else
                <td width="200"></td>
                <td width="200">{{ $i }}.</td>
            @endif
        </tr>
        @php $i++ @endphp
        @endforeach
        @foreach($warakawuri as $w)
        <tr>
            <td>{{ $i }}</td>
            <td>{{ $w->nama }}</td>
            <td>Warakawuri</td>
            <td>{{ $unit->nama }}</td>
            @if($i % 2 == 1)
                <td width="200">{{ $i }}.</td>
                <td width="200"></td>
            @else
                <td width="200"></td>
                <td width="200">{{ $i }}.</td>
            @endif
        </tr>
        @php $i++ @endphp
        @endforeach
    </tbody>
</table>