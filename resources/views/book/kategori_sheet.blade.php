<table>
    <thead>
        <tr>
            <th>Kode Kategori</th>
            <th>Kategori</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kategori as $key => $k)
        <tr>
            <td>{{ $k->id }}</td>
            <td>{{ $k->nama_kategori }}</td>
        </tr> 
        @endforeach
    </tbody>
</table>