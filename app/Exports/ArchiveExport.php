<?php

namespace App\Exports;

use App\Models\Archive;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArchiveExport implements FromQuery, WithHeadings
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
        return Archive::query()
            ->select('title', 'number', 'created_by', 'work_unit', 'created_at')
            ->whereBetween('created_at', [$this->from, $this->to]);
    }

    public function headings(): array
    {
        return [
            'Judul Arsip',
            'Nomor Surat',
            'Dibuat Oleh',
            'Unit Kerja',
            'Dibuat Pada',
        ];
    }
}
