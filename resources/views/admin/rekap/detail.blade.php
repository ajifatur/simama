<table border="1" style="width: 100%">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama</th>
            <th>Unit</th>
            <th colspan="2">Tanda Tangan</th>
        </tr>
    </thead>
    <tbody>
        @foreach($purnakarya as $key=>$p)
        <tr>
            <td>{{ $key + 1 }}</td>
            <td>{{ $p['nama'] }}</td>
            <td>{{ $unit->nama }}</td>
            @if($key % 2 == 0)
                <td width="200">{{ $key + 1 }}.</td>
                <td width="200"></td>
            @else
                <td width="200"></td>
                <td width="200">{{ $key + 1 }}.</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>