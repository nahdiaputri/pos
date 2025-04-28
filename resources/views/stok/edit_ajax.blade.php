<div id="modal-master" class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalFormTitle">Edit Data Stok</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form id="formStokEdit" method="POST" action="{{ url('/stok/' . $stok->stok_id . '/update_ajax') }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="barang_id" class="form-label">Nama Barang</label>
                    <select name="barang_id" id="barang_id" class="form-control" required>
                        <option value="">Pilih Barang</option>
                        @foreach ($barang as $b)
                            <option value="{{ $b->barang_id }}" {{ $stok->barang_id == $b->barang_id ? 'selected' : '' }}>
                                {{ $b->barang_nama }}
                            </option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback" id="feedback-barang_id"></div>
                </div>
                <div class="mb-3">
                    <label for="stok_jumlah" class="form-label">Jumlah Stok</label>
                    <input type="number" class="form-control" id="stok_jumlah" name="stok_jumlah" value="{{ $stok->stok_jumlah }}" min="{{ $stok->stok_jumlah }}" required>
                    <small class="form-text text-muted">Jumlah stok tidak boleh dikurangi. Masukkan angka yang sama atau lebih besar dari sebelumnya ({{ $stok->stok_jumlah }}).</small>
                    <div class="invalid-feedback" id="feedback-stok_jumlah"></div>
                </div>        
                <div class="mb-3">
                    <label for="stok_tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="stok_tanggal" name="stok_tanggal" value="{{ date('Y-m-d', strtotime($stok->stok_tanggal)) }}" required>
                    <div class="invalid-feedback" id="feedback-stok_tanggal"></div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            <button type="button" class="btn btn-primary" id="btnSimpan" onclick="submitForm('formStokEdit')">Simpan</button>
        </div>
    </div>
</div>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function showToast(type, message) {
        Swal.fire({
            icon: type, // 'success', 'error', etc.
            title: message,
            timer: 2500,
            showConfirmButton: false,
            toast: false,
            position: 'center',
        });
    }
    function submitForm(formId) {
        // Reset feedback
        $('.is-invalid').removeClass('is-invalid');
        
        // Ambil data form
        const form = document.getElementById(formId);
        const formData = new FormData(form);
        
        // Kirim request AJAX
        $.ajax({
            url: form.getAttribute('action'),
            type: 'POST', // Ensure the correct method is used
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status) {
                    // Jika berhasil
                    $('#myModal').modal('hide');
                    showToast('success', response.message);
                    $('#datastok').DataTable().ajax.reload();
                } else {
                    // Jika ada error validasi
                    showToast('error', response.message);
                    
                    // Tampilkan feedback error
                    if (response.msgField) {
                        for (const field in response.msgField) {
                            $('#' + field).addClass('is-invalid');
                            $('#feedback-' + field).text(response.msgField[field]);
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                showToast('error', 'Terjadi kesalahan saat memproses data');
                console.error(xhr.responseText);
            }
        });
    }
</script>

