<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Aduan;
use Dompdf\Dompdf;
use Dompdf\Options;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AduanExport;

class ReportController extends Controller
{
    // Metode untuk menghasilkan PDF
    public function generatePdf()
    {
        // Mengambil semua data Aduan dengan relasi user dan kategori
        $aduans = Aduan::with(['user', 'kategori'])->get();

        // Mengatur Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Menggunakan view untuk membangun PDF
        $dompdf->loadHtml(view('reports.aduan', compact('aduans'))->render());

        // (Opsional) Set ukuran dan orientasi kertas
        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->render();

        // Mengunduh file PDF
        return $dompdf->stream('aduan_report.pdf', ['Attachment' => true]);
    }
    
    public function generateExcel()
    {
        // dd('Endpoint generateExcel berhasil dipanggil');
        return Excel::download(new AduanExport, 'aduan_report.xlsx');
    }
}
