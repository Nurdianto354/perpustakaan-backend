<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Buku</th>
            <th>Judul</th>
            <th>Kategori</th>
            <th>Pengarang</th>
            <th>Penerbit</th>
            <th>Tahun</th>
            <th>Stok</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($books as $key => $book)    
        <tr>
            <td>{{ $key+1 }}</td>
            <td>{{ $book->kode_buku }}</td>
            <td>{{ $book->judul }}</td>
            <td>{{ $book->category->nama_kategori }}</td>
            <td>{{ $book->pengarang }}</td>
            <td>{{ $book->penerbit }}</td>
            <td>{{ $book->tahun }}</td>
            <td>{{ $book->stok }}</td>
        </tr>
        @endforeach
    </tbody>
</table>