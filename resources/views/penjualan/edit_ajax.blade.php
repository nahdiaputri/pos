@empty($penjualan)
     <div id="modal-master" class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title">Kesalahan</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                         aria-hidden="true">&times;</span></button>
             </div>
             <div class="modal-body">
                 <div class="alert alert-danger">
                     <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                     Data penjualan tidak ditemukan.
                 </div>
                 <a href="{{ url('/penjualan') }}" class="btn btn-warning">Kembali</a>
             </div>
         </div>
     </div>
 @else
     <form action="{{ url('/penjualan/' . $penjualan->penjualan_id . '/update_ajax') }}" method="POST" id="form-edit">
         @csrf
         @method('PUT')
         <div id="modal-master" class="modal-dialog modal-lg" role="document">
             <div class="modal-content">
                 <div class="modal-header">
                     <h5 class="modal-title">Edit Data Penjualan</h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                             aria-hidden="true">&times;</span></button>
                 </div>
                 <div class="modal-body">
                     <div class="form-group">
                         <label>Kode Penjualan</label>
                         <input value="{{$penjualan->penjualan_kode }}" type="text" name="penjualan_kode" id="penjualan_kode"
                             class="form-control" required>
                         <small id="error-penjualan_kode" class="error-text form-text text-danger"></small>
                     </div>
                     <div class="form-group">
                         <label>Tanggal Penjualan</label>
                         <input value="{{ $penjualan->penjualan_tanggal }}" type="date" name="penjualan_tanggal" id="penjualan_tanggal"
                             class="form-control" required>
                         <small id="error-penjualan_tanggal" class="error-text form-text text-danger"></small>
                     </div>
                     <div class="form-group">
                         <label>Nama Pembeli</label>
                         <input value="{{ $penjualan->pembeli }}" type="text" name="pembeli" id="pembeli"
                             class="form-control" required>
                         <small id="error-pembeli" class="error-text form-text text-danger"></small>
                     </div>
                     <div class="form-group">
                         <label>User</label>
                         <select name="user_id" id="user_id" class="form-control" required>
                             <option value="">Pilih User</option>
                             @foreach ($users as $user)
                                 <option value="{{ $user->user_id }}" {{ $penjualan->user_id == $user->user_id ? 'selected' : '' }}>
                                     {{ $user->username }}
                                 </option>
                             @endforeach
                         </select>
                         <small id="error-user_id" class="error-text form-text text-danger"></small>
                     </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                     <button type="submit" class="btn btn-primary">Simpan</button>
                 </div>
             </div>
         </div>
     </form>
 
     <script>
         $(document).ready(function () {
             $("#form-edit").validate({
                 rules: {
                     penjualan_tanggal: {
                         required: true,
                         date: true
                     },
                     penjualan_kode: {
                         required: true,
                         minlength: 3
                     },
                     pembeli: {
                         required: true,
                         minlength: 3
                     },
                     user_id: {
                         required: true
                     },
                 },
                 submitHandler: function (form) {
                     $.ajax({
                         url: form.action,
                         type: form.method,
                         data: $(form).serialize(),
                         success: function (response) {
                             if (response.status) {
                                 $('#myModal').modal('hide');
                                 Swal.fire({
                                     icon: 'success',
                                     title: 'Berhasil',
                                     text: response.message
                                 });
                                 dataPenjualan.ajax.reload(); // pastikan datatable bernama `dataPenjualan` ada
                             } else {
                                 $('.error-text').text('');
                                 $.each(response.msgField, function (prefix, val) {
                                     $('#error-' + prefix).text(val[0]);
                                 });
                                 Swal.fire({
                                     icon: 'error',
                                     title: 'Gagal',
                                     text: response.message
                                 });
                             }
                         }
                     });
                     return false;
                 },
                 errorElement: 'span',
                 errorPlacement: function (error, element) {
                     error.addClass('invalid-feedback');
                     element.closest('.form-group').append(error);
                 },
                 highlight: function (element) {
                     $(element).addClass('is-invalid');
                 },
                 unhighlight: function (element) {
                     $(element).removeClass('is-invalid');
                 }
             });
         });
     </script>
 @endempty