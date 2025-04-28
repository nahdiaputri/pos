@extends('layouts.app')

@section('content')
<div class="container">
    <h1>{{ $title }}</h1>
    <form action="{{ route('stok.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="id_barang" class="form-label">Nama Barang</label>
            <select name="id_barang" id="id_barang" class="form-control">
                @foreach($barang as $b)
                    <option value="{{ $b->barang_id }}">{{ $b->barang_nama }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="jumlah" class="form-label">Jumlah Stok</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="keterangan" class="form-label">Keterangan</label>
            <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
    </form>
</div>
@endsection