<?php

namespace App\Exports;

use App\Models\DocumentAuthorizationLetter;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DocumentAuthorizationLetterExport implements FromQuery, WithHeadings
{
    private $from;
    private $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function query()
    {
        return DocumentAuthorizationLetter::query()
            ->select('number', 'title', 'contract_number', 'payment_total', 'created_by', 'vendor_name', 'bank_name', 'account_number', 'payment_number', 'created_at')
            ->whereBetween('created_at', [$this->from, $this->to]);
    }

    public function headings(): array
    {
        return [
            'Nomor Surat',
            'Nama Pekerjaan',
            'Nomor PAA',
            'Jumlah Pembayaran',
            'Dibuat Oleh',
            'Nama Vendor',
            'Nama Bank Vendor',
            'Nomor Rekening Vendor',
            'Nomor Nota Dinas Pembayaran',
            'Tanggal Pembuatan',
        ];
    }
}
