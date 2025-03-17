<?php
 
 namespace App\Http\Controllers;
 
 use App\Models\KategoriModel;
 use App\Models\BarangModel;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\Log;
 use Illuminate\Support\Facades\Validator;
 
 class BarangController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Barang',
             'list' => ['Home', 'Barang']
         ];
 
         $page = (object) [
             'title' => 'Daftar Barang yang terdaftar di sistem'
         ];
 
         $activeMenu = 'barang';
 
         $kategori = KategoriModel::all();
 
         return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
     }
 
     public function list(Request $request)
     {
         $barang = BarangModel::select('barang_id', 'barang_kode', 'barang_nama', 'kategori_id')->with('kategori');
 
         if ($request->kategori_id) {
             $barang->where('kategori_id', $request->kategori_id);
         }
 
         return DataTables::of($barang)
             ->addIndexColumn()
             ->addColumn('aksi', function ($barang) {
                 //$btn = '<a href="' . url('/barang/' . $barang->barang_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                //  $btn .= '<a href="' . url('/barang/' . $barang->barang_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                //  $btn .= '<form class="d-inline-block" method="POST" action="' . url('/barang/' . $barang->barang_id) . '">'
                //      . csrf_field() . method_field('DELETE') .
                //      '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                $btn = '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/barang/' . $barang->barang_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Barang',
             'list' => ['Home', 'Barang', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah Barang baru'
         ];
 
         $activeMenu = 'barang';
 
         $kategori = KategoriModel::all();
 
         return view('barang.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'kategori' => $kategori]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'kategori_id' => 'required',
             'barang_kode' => 'required|unique:m_barang',
             'barang_nama' => 'required',
             'harga_beli' => 'required',
             'harga_jual' => 'required',
         ]);
 
         BarangModel::create($request->all());
 
         return redirect('/barang')->with('success', 'Data berhasil ditambahkan');
     }
 
     public function show($id)
     {
         $breadcrumb = (object) [
             'title' => 'Detail Barang',
             'list' => ['Home', 'Barang', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail Barang yang terdaftar di sistem'
         ];
 
         $activeMenu = 'barang';
 
         $barang = BarangModel::with('kategori')->find($id);
 
         return view('barang.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang' => $barang]);
     }
 
     public function edit($id)
     {
         $breadcrumb = (object) [
             'title' => 'Edit Barang',
             'list' => ['Home', 'Barang', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Barang yang terdaftar di sistem'
         ];
 
         $activeMenu = 'barang';
 
         $barang = BarangModel::find($id);
         $kategori = KategoriModel::all();
 
         return view('barang.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'barang' => $barang, 'kategori' => $kategori]);
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'kategori_id' => 'required',
             'barang_kode' => 'required|unique:m_barang,barang_kode,' . $id . ',barang_id',
             'barang_nama' => 'required',
             'harga_beli' => 'required',
             'harga_jual' => 'required',
         ]);
 
         BarangModel::find($id)->update($request->all());
 
         return redirect('/barang')->with('success', 'Data berhasil diubah');
     }
 
     public function destroy($id)
     {
         $check = BarangModel::find($id);
         if (!$check) {
             return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
         }
 
         try {
             BarangModel::destroy($id);
 
             return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
         } catch (\Exception $e) {
             return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }
     public function create_ajax()
     {
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
 
         return view('barang.create_ajax')->with('kategori', $kategori);
     }
 
     public function store_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_id' => 'required|exists:m_kategori,kategori_id',
                 'barang_kode' => 'required|min:3|unique:m_barang,barang_kode',
                 'barang_nama' => 'required|min:3',
                 'harga_beli' => 'required|numeric|min:1',
                 'harga_jual' => 'required|numeric|min:1',
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(),
                 ]);
             }
 
             BarangModel::create($request->all());
             return response()->json([
                 'status' => true,
                 'message' => 'Data barang berhasil disimpan'
             ]);
         }
         redirect('/');
     }

     public function show_ajax(string $id)
     {
         $barang = BarangModel::with('kategori')->find($id);
         return view('barang.show_ajax', ['barang' => $barang]);
     }
 
     public function edit_ajax(string $id)
     {
         $barang = BarangModel::find($id);
         $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
 
         return view('barang.edit_ajax')->with('barang', $barang)->with('kategori', $kategori);
     }
 
     public function update_ajax(Request $request, string $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'kategori_id' => 'required|exists:m_kategori,kategori_id',
                 'barang_kode' => 'required|min:3|unique:m_barang,barang_kode,' . $id . ',barang_id',
                 'barang_nama' => 'required|min:3',
                 'harga_beli' => 'required|numeric|min:1',
                 'harga_jual' => 'required|numeric|min:1',
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(),
                 ]);
             }
 
             $check = BarangModel::find($id);
             if ($check) {
                 $check->update($request->all());
                 return response()->json([
                     'status' => true,
                     'message' => 'Data berhasil diupdate'
                 ]);
             } else {
                 return response()->json([
                     'status' => false,
                     'message' => 'Data tidak ditemukan'
                 ]);
             }
         }
         return redirect('/');
     }
 
     public function confirm_ajax(string $id)
     {
         $barang = BarangModel::find($id);
 
         return view('barang.confirm_ajax')->with('barang', $barang);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $check = BarangModel::find($id);
             try {
                $check = BarangModel::find($id);
                if ($check) {
                    $check->delete();
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil dihapus'
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak ditemukan'
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('Error deleting user: ' . $e->getMessage());
                if (str_contains($e->getMessage(), 'SQLSTATE[23000]')) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Data tidak dapat dihapus karena masih terkait dengan data lain di sistem'
                    ]);
                }
            }
        }
        return redirect('/');
     }
 }