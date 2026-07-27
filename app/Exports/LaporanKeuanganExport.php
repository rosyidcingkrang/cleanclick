<?php

namespace App\Exports;

use App\Models\Transaksi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanKeuanganExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithCustomStartCell
{
    protected $tanggal;

    public function __construct($tanggal = null)
    {
        $this->tanggal = $tanggal;
    }

    public function collection()
    {
        // Load relasi user dan layanan agar data nama dan layanan bisa diambil
        $query = Transaksi::with(['user', 'layanan']);

        if ($this->tanggal) {
            $query->where(function($q) {
                $q->whereDate('tanggal', $this->tanggal)
                  ->orWhereDate('created_at', $this->tanggal);
            });
        }

        return $query->orderBy('id_transaksi', 'desc')->get();
    }

    public function startCell(): string
    {
        return 'A5'; // Tabel dimulai dari baris ke-5
    }

    public function headings(): array
    {
        return [
            'No Nota',
            'Tanggal',
            'Nama Pelanggan',
            'Layanan',
            'Jumlah / Berat',
            'Status Bayar',
            'Status Cucian',
            'Total Harga'
        ];
    }

    public function map($item): array
    {
        // Ambil nama pelanggan dari relasi user, fallback jika user terhapus/offline
        $namaPelanggan = $item->user ? $item->user->name : 'Pelanggan Walk-in';

        // Ambil nama layanan dari relasi layanan
        $namaLayanan = $item->layanan ? ($item->layanan->nama_layanan ?? $item->layanan->nama ?? '-') : '-';

        // Tanggal Transaksi
        $tanggalFormat = $item->tanggal ? $item->tanggal : ($item->created_at ? $item->created_at->format('Y-m-d') : '-');

        return [
            $item->no_nota ?? 'INV-' . $item->id_transaksi,
            $tanggalFormat,
            ucwords(strtolower($namaPelanggan)),
            $namaLayanan,
            (float) ($item->quantity ?? $item->jumlah ?? 1),
            $item->status_pembayaran ?? 'Belum Lunas',
            $item->status_cucian ?? 'Antrean',
            (float) ($item->total_harga ?? 0),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Total Pendapatan Lunas untuk Ringkasan Atas
        $totalLunas = Transaksi::when($this->tanggal, function($q) {
            return $q->where(function($sub) {
                $sub->whereDate('tanggal', $this->tanggal)
                    ->orWhereDate('created_at', $this->tanggal);
            });
        })->where('status_pembayaran', 'Lunas')->sum('total_harga');

        // Header Informasi Atas
        $sheet->setCellValue('A1', 'LAPORAN KEUANGAN CLEANCLICK LAUNDRY');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A2', 'Filter Tanggal: ' . ($this->tanggal ?? 'Semua Tanggal'));
        $sheet->setCellValue('A3', 'Total Pendapatan (Lunas): Rp ' . number_format($totalLunas, 0, ',', '.'));
        $sheet->getStyle('A3')->getFont()->setBold(true)->getColor()->setRGB('0284C7');

        // Style Header Tabel (Baris A5:H5)
        $sheet->getStyle('A5:H5')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '0F172A'], // Dark Navy
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);

        $lastRow = $sheet->getHighestRow();

        if ($lastRow >= 6) {
            // Format Rupiah untuk Kolom Total Harga (Kolom H)
            $sheet->getStyle('H6:H' . $lastRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');

            // Format Desimal untuk Jumlah/Berat (Kolom E)
            $sheet->getStyle('E6:E' . $lastRow)->getNumberFormat()->setFormatCode('#,##0.00');

            // Alignment Tengah untuk No Nota, Tanggal, Status Bayar, Status Cucian
            $sheet->getStyle('A6:B' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle('F6:G' . $lastRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

            // Border Thin untuk Seluruh Tabel
            $sheet->getStyle('A5:H' . $lastRow)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

            // Tambahkan Baris TOTAL KESELURUHAN di Bawah
            $summaryRow = $lastRow + 1;
            $sheet->setCellValue('A' . $summaryRow, 'TOTAL KESELURUHAN');
            $sheet->mergeCells('A' . $summaryRow . ':G' . $summaryRow);
            $sheet->getStyle('A' . $summaryRow)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
            $sheet->getStyle('A' . $summaryRow)->getFont()->setBold(true);

            // Rumus Excel SUM Otomatis
            $sheet->setCellValue('H' . $summaryRow, '=SUM(H6:H' . $lastRow . ')');
            $sheet->getStyle('H' . $summaryRow)->getNumberFormat()->setFormatCode('"Rp "#,##0');
            $sheet->getStyle('H' . $summaryRow)->getFont()->setBold(true);

            // Border Ganda di Bawah Baris Total
            $sheet->getStyle('A' . $summaryRow . ':H' . $summaryRow)->getBorders()->getBottom()->setBorderStyle(Border::BORDER_DOUBLE);
            $sheet->getStyle('A' . $summaryRow . ':H' . $summaryRow)->getBorders()->getTop()->setBorderStyle(Border::BORDER_THIN);
        }

        return [];
    }
}