@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title">{{ $breadcrumb->title }}</h3>
            <div class="card-tools"> 
                <a href="{{ url('/stok/export_excel') }}" class="btn btn-primary"><i class="fa fa-file-excel"></i> Export Excel</a>
                <a href="{{ url('/stok/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export PDF</a>
                <button onclick="modalAction('{{ url('/stok/create_ajax') }}')" class="btn btn-success">Tambah Data Ajax</button>      
            </div>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <table class="table table-bordered table-striped table-hover table-sm" id="table_stok">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Barang</th>
                        <th>Jumlah</th>
                        <th>User</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
<script>
    function modalAction(url = '') {
        $('#myModal').load(url, function () {
            $('#myModal').modal('show');
        });
    }



    $.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

datastok = $('#table_stok').DataTable({
    serverSide: true,
    ajax: {
        "url": "{{ url('stok/list') }}",
        "type": "POST",
    },
    columns: [
        {
            data: "DT_RowIndex",
            className: "text-center",
            orderable: false,
            searchable: false
        },
        {
            data: "barang.barang_nama",
            className: "",
            orderable: true,
            searchable: true
        },
    
        {
            data: "stok_jumlah",
            className: "",
            orderable: true,
            searchable: true
        },
        {
        data: "user.username",
        className: "",
        orderable: true,
        searchable: true
    },
        {
            data: "aksi",
            className: "",
            orderable: false,
            searchable: false
        }
    ]
});

$('#myModal').on('hidden.bs.modal', function () {
    datastok.ajax.reload(null, false);
});

</script>
@endpush
