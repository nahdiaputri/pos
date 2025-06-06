<?php
 
 namespace App\Http\Controllers;
 
use App\Models\LevelModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;
 class BarangController extends Controller
 {
     public function index()
     {
        $activeMenu = 'barang';
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];
        $kategori = KategoriModel::select('kategori_id', 'kategori_nama')->get();
        return view('barang.index', [
        'activeMenu' => $activeMenu,
        'breadcrumb' => $breadcrumb,
        'kategori' => $kategori
        ]);
    }
 
    public function list(Request $request)
    {
        $barang = BarangModel::select('barang_id', 'barang_kode', 'harga_beli', 'harga_jual', 'barang_nama', 'kategori_id')->with('kategori');
 
        $kategori_id = $request->input('filter_kategori');
        if(!empty($kategori_id)){
        $barang->where('kategori_id', $kategori_id);
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
    public function import()
    {
    return view('barang.import');
    }
    public function import_ajax(Request $request)
    {
        if($request->ajax() || $request->wantsJson()){
            $rules = [
            // validasi file harus xls atau xlsx, max 1MB
            'file_barang' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }
            $file = $request->file('file_barang'); // ambil file dari request
            $reader = IOFactory::createReader('Xlsx'); // load reader file excel
            $reader->setReadDataOnly(true); // hanya membaca data
            $spreadsheet = $reader->load($file->getRealPath()); // load file excel
            $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
            $data = $sheet->toArray(null, false, true, true); // ambil data excel
            $insert = [];
            if(count($data) > 1){ // jika data lebih dari 1 baris
                foreach ($data as $baris => $value) {
                    if($baris > 1){ // baris ke 1 adalah header, maka lewati
                        $insert[] = [
                            'kategori_id' => $value['A'],
                            'barang_kode' => $value['B'],
                            'barang_nama' => $value['C'],
                            'harga_beli' => $value['D'],
                            'harga_jual' => $value['E'],
                            'created_at' => now(),
                        ];
                    }
                }
                if(count($insert) > 0){
                    // insert data ke database, jika data sudah ada, maka diabaikan
                    BarangModel::insertOrIgnore($insert);
                }
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            }else{
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel() {
        //ambil data barang yang akan di eksport
        $barang = BarangModel::select('kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')
        ->orderBy('kategori_id')
        ->with('kategori')
        ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();       // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true);     // bold header

        $no = 1;        // nomor data dimulai dari 1
        $baris = 2;     // baris data dimulai dari baris ke 2
        foreach ($barang as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->barang_kode);
            $sheet->setCellValue('C' . $baris, $value->barang_nama);
            $sheet->setCellValue('D' . $baris, $value->harga_beli);
            $sheet->setCellValue('E' . $baris, $value->harga_jual);
            $sheet->setCellValue('F' . $baris, $value->kategori->kategori_nama); // ambil nama kategori
            $baris++;
            $no++;
        }

        foreach (range('A','F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        $sheet->setTitle('Data Barang'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Barang ' . date('Y-m-d H:i:s') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT');
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }
    public function export_pdf()
    {
        $barang = BarangModel::select('barang_kode', 'barang_nama', 'harga_beli','harga_jual', 'kategori_id')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();

        $pdf = Pdf::loadView('barang.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('a4', 'portrait');
        $pdf->setOption("isRemoteEnbled", true);
        $pdf->render();

        return $pdf->stream('Data Barang'.date('Y-m-d').'.pdf');
    }
}