@empty($stok)

     <div id="modal-master" class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Kesalahan</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                 <div class="alert alert-danger">
                     <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                     Data yang anda cari tidak ditemukan
                 </div>
                 <a href="{{ url('/stok') }}" class="btn btn-warning">Kembali</a>
             </div>
         </div>
     </div>
 @else
     <div id="modal-master" class="modal-dialog modal-lg" role="document">
         <div class="modal-content">
             <div class="modal-header">
                 <h5 class="modal-title" id="exampleModalLabel">Detail Stok</h5>
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                 </button>
             </div>
             <div class="modal-body">
                <table class="table table-bordered">
                    <tr>
                        <th>ID Stok</th>
                        <td>{{ $stok->stok_id }}</td>
                    </tr>
                    <tr>
                        <th>ID Barang</th>
                        <td>{{ $stok->barang_id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Barang</th>
                        <td>{{ $stok->barang->barang_nama }}</td>
                    </tr>
                    <tr>
                       <th>Jumlah Stok</th>
                       <td>{{ $stok->stok_jumlah }}</td>
                   </tr>
                   <tr>
                       <th>Tanggal Stok</th>
                       <td>{{ $stok->stok_tanggal }}</td>
                   </tr>
                </table>
             </div>
             <div class="modal-footer">
                 <button type="button" data-dismiss="modal" class="btn btn-primary">Tutup</button>
             </div>
         </div>
     </div>
 @endempty