<?php
 
 namespace App\Http\Controllers;
 
 use Illuminate\Http\Request;
 use App\Models\SupplierModel;
 use Illuminate\Support\Facades\Log;
 use Yajra\DataTables\Facades\DataTables;
 use Illuminate\Support\Facades\Validator;
 use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;

 
 class SupplierController extends Controller
 {
     public function index()
     {
         $breadcrumb = (object) [
             'title' => 'Daftar Supplier',
             'list' => ['Home', 'Supplier']
         ];
 
         $page = (object) [
             'title' => 'Daftar Supplier yang terdaftar di sistem'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function list(Request $request)
     {
         $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');
 
         if ($request->supplier_nama) {
             $supplier->where('supplier_nama', 'like', '%' . $request->supplier_nama . '%');
         }
 
         return DataTables::of($supplier)
         ->addIndexColumn()
         ->addColumn('aksi', function ($supplier) {
                // $btn = '<a href="' . url('/supplier/' . $supplier->supplier_id) . '" class="btn btn-info btn-sm">Detail</a> ';
                 // $btn .= '<a href="' . url('/supplier/' . $supplier->supplier_id . '/edit') . '" class="btn btn-warning btn-sm">Edit</a> ';
                 // $btn .= '<form class="d-inline-block" method="POST" action="' . url('/supplier/' . $supplier->supplier_id) . '">'
                 //     . csrf_field() . method_field('DELETE') .
                 //     '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\');">Hapus</button></form>';
                 $btn = '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                 $btn .= '<button onclick="modalAction(\'' . url('/supplier/' . $supplier->supplier_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                 return $btn;
             })
             ->rawColumns(['aksi'])
             ->make(true);
     }
 
     public function create()
     {
         $breadcrumb = (object) [
             'title' => 'Tambah Supplier',
             'list' => ['Home', 'Supplier', 'Tambah']
         ];
 
         $page = (object) [
             'title' => 'Tambah Supplier Baru'
         ];
 
         $activeMenu = 'supplier';
 
         return view('supplier.create', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
     }
 
     public function store(Request $request)
     {
         $request->validate([
             'supplier_kode' => 'required',
             'supplier_nama' => 'required',
             'supplier_alamat' => 'required'
         ]);
 
         SupplierModel::create($request->all());
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil ditambahkan');
     }
 
     public function show($id)
     {
         $breadcrumb = (object) [
             'title' => 'Detail Supplier',
             'list' => ['Home', 'Supplier', 'Detail']
         ];
 
         $page = (object) [
             'title' => 'Detail Supplier'
         ];
 
         $activeMenu = 'supplier';
 
         $supplier = SupplierModel::find($id);
 
         return view('supplier.show', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'supplier' => $supplier]);
     }
 
     public function edit($id)
     {
         $breadcrumb = (object) [
             'title' => 'Edit Supplier',
             'list' => ['Home', 'Supplier', 'Edit']
         ];
 
         $page = (object) [
             'title' => 'Edit Supplier'
         ];
 
         $activeMenu = 'supplier';
 
         $supplier = SupplierModel::find($id);
 
         return view('supplier.edit', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'supplier' => $supplier]);
     }
 
     public function update(Request $request, $id)
     {
         $request->validate([
             'supplier_kode' => 'required|unique:m_supplier',
             'supplier_nama' => 'required',
             'supplier_alamat' => 'required'
         ]);
 
         SupplierModel::find($id)->update($request->all());
 
         return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
     }
 
     public function destroy($id)
     {
         $check = SupplierModel::find($id);
         if (!$check) {
             return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
         }
 
         try {
             SupplierModel::destroy($id);
 
             return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
         } catch (\Exception $e) {
             return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
         }
     }
     public function create_ajax()
     {
         return view('supplier.create_ajax');
     }
 
     public function store_ajax(Request $request)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'supplier_kode' => 'required|unique:m_supplier',
                 'supplier_nama' => 'required',
                 'supplier_alamat' => 'required'
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(),
                 ]);
             }
 
             SupplierModel::create($request->all());
             return response()->json([
                 'status' => true,
                 'message' => 'Data Supplier berhasil disimpan'
             ]);
         }
         redirect('/');
     }
     public function show_ajax(string $id)
     {
         $supplier = SupplierModel::find($id);
         return view('supplier.show_ajax', ['supplier' => $supplier]);
     }
 
     public function edit_ajax($id)
     {
         $supplier = SupplierModel::find($id);
 
         return view('supplier.edit_ajax', ['supplier' => $supplier]);
     }
 
     public function update_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
             $rules = [
                 'supplier_kode' => 'required|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
                 'supplier_nama' => 'required',
                 'supplier_alamat' => 'required'
             ];
 
             $validator = Validator::make($request->all(), $rules);
 
             if ($validator->fails()) {
                 return response()->json([
                     'status' => false,
                     'message' => 'Validasi Gagal',
                     'msgField' => $validator->errors(),
                 ]);
             }
 
             SupplierModel::find($id)->update($request->all());
             return response()->json([
                 'status' => true,
                 'message' => 'Data Supplier berhasil diubah'
             ]);
         }
         redirect('/');
     }
 
     public function confirm_ajax($id)
     {
         $supplier = SupplierModel::find($id);
 
         return view('supplier.confirm_ajax', ['supplier' => $supplier]);
     }
 
     public function delete_ajax(Request $request, $id)
     {
         if ($request->ajax() || $request->wantsJson()) {
            try {
                $check = SupplierModel::find($id);
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
        return view('supplier.import');
    }

    public function import_ajax(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_supplier' => ['required', 'mimes:xlsx', 'max:1024']
            ];

            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_supplier');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris > 1) {
                        $insert[] = [
                            'supplier_id' => $value['A'],
                            'supplier_kode' => $value['B'],
                            'supplier_nama' => $value['C'],
                            'created_at' => now(),
                        ];
                    }
                }

                if (count($insert) > 0) {
                    supplierModel::insertOrIgnore($insert);
                }

                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diimport'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Tidak ada data yang diimport'
                ]);
            }
        }
        return redirect('/');
    }

    public function export_excel()
     {
         // ambil data supplier yang akan di export
         $supplier = supplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat')
             ->orderBy('supplier_id')
             ->get();
 
             // load library excel
             $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
             $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif
 
             $sheet->setCellValue('A1', 'No');
             $sheet->setCellValue('B1', 'Kode supplier');
             $sheet->setCellValue('C1', 'Nama supplier');
            $sheet->setCellValue('D1', 'Alamat supplier');
 
             $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header
 
             $no = 1;         // nomor data dimulai dari 1
             $baris = 2;      // baris data dimulai dari baris ke 2
 
         foreach ($supplier as $key => $value) {
             $sheet->setCellValue('A' . $baris, $no);
             $sheet->setCellValue('B' . $baris, $value->supplier_kode);
             $sheet->setCellValue('C' . $baris, $value->supplier_nama);
            $sheet->setCellValue('D' . $baris, $value->supplier_alamat);
             $baris++;
             $no++;
         }
 
         foreach (range('A', 'F') as $columnID) {
             $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
         }
 
         $sheet->setTitle('Data supplier'); // set title sheet
 
         $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
         $filename = 'Data supplier ' . date('Y-m-d H:i:s') . '.xlsx';
 
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
     } // end function export_excel

     public function export_pdf()
     {
         $supplier = supplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat')
                     ->orderBy('supplier_id')
                     ->get();
 
         // use Barryvdh\DomPDF\Facade\Pdf;
         $pdf = Pdf::loadView('supplier.export_pdf', ['supplier' => $supplier]);
         $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
         $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
         $pdf->render();
        return $pdf->stream('Data_supplier'.date('Y-m-d').'.pdf');
    }
 }