<?php

namespace App\Http\Controllers;

use App\Models\StokModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StokController extends Controller
{
    public function index()
    {
        $activeMenu = 'stok';
        $breadcrumb = (object) [
            'title' => 'Daftar Stok Barang',
            'list' => ['Home', 'Stok']
        ];
        $barang = BarangModel::select('barang_id', 'barang_nama')->get();
        return view('stok.index', compact('activeMenu', 'breadcrumb', 'barang'));
    }



    public function list(Request $request)
    {
        $stok = StokModel::with(['barang', 'user'])->select('stok_id', 'barang_id', 'stok_jumlah', 'stok_tanggal', 'user_id');

        return DataTables::of($stok)
            ->addIndexColumn()
            ->addColumn('barang_nama', function ($stok) {
                return $stok->barang->barang_nama ?? '-';
            })
            ->addColumn('user_name', function ($stok) {
                return $stok->user->name ?? '-';
            })
            ->addColumn('aksi', function ($stok) {
                $btn = '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/show_ajax') . '\')" class="btn btn-info btn-sm">Detail</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/edit_ajax') . '\')" class="btn btn-warning btn-sm">Edit</button> ';
                $btn .= '<button onclick="modalAction(\'' . url('/stok/' . $stok->stok_id . '/delete_ajax') . '\')" class="btn btn-danger btn-sm">Hapus</button> ';
                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function show_ajax(string $id)
    {
        $stok = StokModel::with('barang')->find($id);
        return view('stok.show_ajax', ['stok' => $stok]);
    }

    public function create_ajax()
    {
        $barang = BarangModel::whereDoesntHave('stok')->get();



        return view('stok.create_ajax')->with('barang', $barang);
    }

    public function store_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'barang_id' => 'required',
            'stok_jumlah' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        StokModel::create([
            'barang_id' => $request->barang_id,
            'stok_jumlah' => $request->stok_jumlah,
            'stok_tanggal' => now(), // gunakan waktu saat ini
            'user_id' => Auth::id(), // ambil id user yang sedang login
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data stok berhasil ditambahkan!',
        ]);
    }

    return redirect('/');
}

    public function confirm_ajax(String $id)
    {
        $stok = StokModel::with('barang')->find($id);
        return view('stok.confirm_ajax', ['stok' => $stok]);
    }

    public function delete_ajax(Request $request, $id)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $stok = StokModel::find($id);
            if ($stok) {
                $stok->delete();
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
        }
        return redirect('/');
    }

    public function edit_ajax(string $id)
{
    $stok = StokModel::find($id);
    $barang = BarangModel::select('barang_id', 'barang_nama')->get();

    return view('stok.edit_ajax')->with('stok', $stok)->with('barang', $barang);
}

public function update_ajax(Request $request, $id)
{
    if ($request->ajax() || $request->wantsJson()) {
        $rules = [
            'barang_id' => 'required',
            'stok_jumlah' => 'required|numeric|min:' . $request->stok_jumlah, // Ensure the new stock is not less than the current stock
            'stok_tanggal' => 'required|date',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors(),
            ]);
        }

        $stok = StokModel::find($id);

        if (!$stok) {
            return response()->json([
                'status' => false,
                'message' => 'Data stok tidak ditemukan.',
            ]);
        }

        $stok->update([
            'barang_id' => $request->barang_id,
            'stok_jumlah' => $request->stok_jumlah,
            'stok_tanggal' => $request->stok_tanggal,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Data stok berhasil diperbarui!',
        ]);
    }

    return redirect('/');
}






    public function export_excel()
    {

        $stok = StokModel::select('stok_id', 'barang_id', 'stok_jumlah', 'stok_tanggal', 'user_id')
            ->orderBy('stok_id')
            ->get();

        // load library excel
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'ID STOK');
        $sheet->setCellValue('C1', 'ID BARANG');
        $sheet->setCellValue('D1', 'JUMLAH');
        $sheet->setCellValue('E1', 'TANGGAL');
        $sheet->setCellValue('F1', 'USER ID');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); // bold header

        $no = 1;         // nomor data dimulai dari 1
        $baris = 2;      // baris data dimulai dari baris ke 2

        foreach ($stok as $key => $value) {
            $sheet->setCellValue('A' . $baris, $no);
            $sheet->setCellValue('B' . $baris, $value->stok_id);
            $sheet->setCellValue('C' . $baris, $value->barang_id);
            $sheet->setCellValue('D' . $baris, $value->stok_jumlah);
            $sheet->setCellValue('E' . $baris, $value->stok_tanggal);
            $sheet->setCellValue('F' . $baris, $value->user_id);
            $baris++;
            $no++;
        }

        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set auto size untuk kolom
        }

        $sheet->setTitle('Data Stok'); // set title sheet

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Stok ' . date('Y-m-d H:i:s') . '.xlsx';

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
        $stok = StokModel::select('stok_id', 'barang_id', 'stok_jumlah', 'stok_tanggal', 'user_id')
            ->orderBy('stok_id')
            ->get();

        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('stok.export_pdf', ['stok' => $stok]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption('isRemoteEnabled', true); // set true jika ada gambar dari url
        $pdf->render();
        return $pdf->stream('Data_stok' . date('Y-m-d') . '.pdf');
    }
}
