<form action="{{ url('/penjualan/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Penjualan</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Penjualan</label>
                    <input type="text" name="penjualan_kode" class="form-control" required>
                    <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Pembeli</label>
                    <input type="text" name="pembeli" class="form-control" required>
                    <small id="error-pembeli" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Tanggal Penjualan</label>
                    <input type="date" name="penjualan_tanggal" class="form-control" required>
                    <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                </div>

                <hr>
                <h5>Detail Barang</h5>
                <table class="table table-bordered" id="tabel-barang">
                    <thead>
                        <tr>
                            <th>Barang</th>
                            <th>Harga Jual</th>
                            <th>Jumlah</th>
                            <th><button type="button" class="btn btn-success btn-sm" id="tambah-barang">+</button></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <select name="barang_id[]" class="form-control" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach ($barangs as $barang)
                                        <option value="{{ $barang->barang_id }}" data-harga="{{ $barang->harga_jual }}">
                                            {{ $barang->barang_nama }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="error-text form-text text-danger"></small>
                            </td>
                            <td>
                                <input type="number" name="harga[]" class="form-control" readonly>
                                <small class="error-text form-text text-danger"></small>
                            </td>
                            <td>
                                <input type="number" name="jumlah[]" class="form-control" required>
                                <small class="error-text form-text text-danger"></small>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm hapus-barang">x</button>
                            </td>
                        </tr>
                    </tbody>
                </table>

            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    // Tambah baris barang
    $('#tambah-barang').click(function() {
        const baris = `
        <tr>
            <td>
                <select name="barang_id[]" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $barang)
                        <option value="{{ $barang->barang_id }}" data-harga="{{ $barang->harga_jual }}">
                            {{ $barang->barang_nama }}
                        </option>
                    @endforeach
                </select>
                <small class="error-text form-text text-danger"></small>
            </td>
            <td>
                <input type="number" name="harga[]" class="form-control" required readonly>
                <small class="error-text form-text text-danger"></small>
            </td>
            <td>
                <input type="number" name="jumlah[]" class="form-control" required>
                <small class="error-text form-text text-danger"></small>
            </td>
            <td>
                <button type="button" class="btn btn-danger btn-sm hapus-barang">x</button>
            </td>
        </tr>`;

        $('#tabel-barang tbody').append(baris);
    });

    // Hapus baris barang
    $(document).on('click', '.hapus-barang', function() {
        $(this).closest('tr').remove();
    });

    // Validasi & Submit
    $("#form-tambah").validate({
        rules: {
            penjualan_kode: {
                required: true
            },
            pembeli: {
                required: true
            },
            penjualan_tanggal: {
                required: true
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize(),
                success: function(response) {
                    if (response.status) {
                        $('#myModal').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message
                        });
                        dataPenjualan.ajax.reload(); // sesuaikan ini dengan datatable kamu
                    } else {
                        $('.error-text').text('');
                        if (response.msgField) {
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                        }
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                }
            });
            return false;
        }
    });
     // Saat select barang berubah, update harga
     $(document).on('change', 'select[name="barang_id[]"]', function () {
        const selected = $(this).find(':selected');
        const harga = selected.data('harga') || 0;

        // cari input harga di baris yang sama
        $(this).closest('tr').find('input[name="harga[]"]').val(harga);
    });
    $('select[name="barang_id[]"]').trigger('change');
</script>