<?php

namespace App\Http\Controllers;

use App\Models\DocumentAuthorizationLetter;
use App\Models\Vendor;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Ilovepdf\Ilovepdf;
use IntlDateFormatter;
use PhpOffice\PhpWord\TemplateProcessor;
use setasign\Fpdi\Fpdi;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class DocumentAuthorizationLetterController extends Controller
{
    public function index()
    {
        $documentAuthorizationLetters = DocumentAuthorizationLetter::where('contract_number', 'SUL')->orderBy('created_at', 'DESC')->get();

        return view('pages.documentauthorizationletter.documentauthorizationletters', [
            'documentauthorizationletters' => $documentAuthorizationLetters,
        ]);
    }

    public function showCreatePage()
    {
        $vendors = Vendor::all();

        return view('pages.documentauthorizationletter.createdocumentauthorizationletter', [
            'documentAuthorizationLetterNumber' => $this->documentAuthorizationLetterNumbering(),
            'vendors' => $vendors,
        ]);
    }

    public function documentAuthorizationLetterNumbering()
    {
        $date = time();
        $unitKerja = Auth::user()->work_unit;

        $records = DocumentAuthorizationLetter::whereMonth('created_at', date('m', $date))
            ->where('number', 'LIKE', '%' . $unitKerja . '%')
            ->orderBy('number', 'DESC')
            ->get();

        if (count($records) != 0) {
            $lastRecordData = $records->first();
            $letterNumber = $lastRecordData->number;
            $letterNumber = (int) substr($letterNumber, 0, 3);
            $letterNumber += 1;
            $newLetterNumber = str_pad($letterNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $newLetterNumber = str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        $month = $this->numberToRomanRepresentation(date('n', $date));

        if ($unitKerja == 'WIL4') {
            $template = sprintf('%s/WIL4/KD/%s/%s', $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        } else {
            $template = sprintf('%s/WIL4-%s/KD/%s/%s', $newLetterNumber, strtoupper($unitKerja), $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        }

        return $template;
    }

    public function documentAuthorizationLetterNumber()
    {
        $lastRecordData = DocumentAuthorizationLetter::latest()->first();

        if ($lastRecordData != null) {
            $lastDataMonth = $lastRecordData->value('created_at')->month;

            if ($lastDataMonth != date('n')) {
                $newLetterNumber = str_pad(1, 3, '0', STR_PAD_LEFT);
            } else {
                $letterNumber = $lastRecordData->number;
                $letterNumber = (int) substr($letterNumber, 0, 3);
                $letterNumber += 1;
                $newLetterNumber = str_pad($letterNumber, 3, '0', STR_PAD_LEFT);
            }
        } else {
            $newLetterNumber = str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        return $newLetterNumber;
    }

    public function numberToRomanRepresentation($number)
    {
        $map = ['M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1];
        $returnValue = '';
        while ($number > 0) {
            foreach ($map as $roman => $int) {
                if ($number >= $int) {
                    $number -= $int;
                    $returnValue .= $roman;
                    break;
                }
            }
        }
        return $returnValue;
    }

    public function selectVendor(Request $request)
    {
        $vendor = Vendor::find($request->vendorId);

        return response()->json([
            'vendor' => $vendor,
        ]);
    }

    public function documentAuthorizationLetterNumberingLive(Request $request)
    {
        $date = strtotime($request->dateData);
        $unitKerja = $request->unitKerjaData;

        $records = DocumentAuthorizationLetter::whereMonth('created_at', date('m', $date))
            ->where('number', 'LIKE', '%' . $unitKerja . '%')
            ->orderBy('number', 'DESC')
            ->get();

        if (count($records) != 0) {
            $lastRecordData = $records->first();
            $letterNumber = $lastRecordData->number;
            $letterNumber = (int) substr($letterNumber, 0, 3);
            $letterNumber += 1;
            $newLetterNumber = str_pad($letterNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $newLetterNumber = str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        $month = $this->numberToRomanRepresentation(date('n', $date));

        if ($unitKerja == 'wil4') {
            $template = sprintf('%s/WIL4/KD/%s/%s', $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        } else {
            $template = sprintf('%s/WIL4-%s/KD/%s/%s', $newLetterNumber, strtoupper($unitKerja), $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        }

        return response()->json([
            'documentAuthorizationLetterNumber' => $template,
        ]);
    }

    private function deleteFileIfExists($filePath)
    {
        if (File::exists($filePath)) {
            File::delete($filePath);
        }
    }

    public function create(Request $request)
    {
        $data = $request->all();
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'unique' => ':attribute yang diinput sudah terdaftar',
        ];

        $this->validate(
            $request,
            [
                'nomorSurat' => 'required|unique:document_authorization_letters,number',
                'namaSurat' => 'required|min:10',
                'tanggalPembuatan' => 'required',
                'nomorKontrak' => 'required',
                'namaVendor' => 'required',
                'jumlahPembayaran' => 'required',
                'bankPenerima' => 'required',
                'nomorRekening' => 'required',
                'nomorNotaDinasPembayaran' => 'required',
            ],
            $messages,
        );
        $user = Auth::user();
        $locale = 'id_ID';
        $date = new DateTime($data['tanggalPembuatan']);
        $dateFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);

        try {
            $nomorSurat = $data['nomorSurat'];
            $tanggal = $dateFormatter->format($date);
            $namaSurat = $data['namaSurat'];
            $nomorKontrak = $data['nomorKontrak'];
            $jumlahPembayaran = str_replace(',', '.', $data['jumlahPembayaran']);
            $bankPenerima = $data['bankPenerima'];
            $nomorRekening = $data['nomorRekening'];
            $namaVendor = $data['namaVendor'];
            $namaVendor = strtoupper($namaVendor);
            $unitKerja = $user->role == 1 ? $data['unitKerja'] : $user->work_unit;
            $nomorNotaDinasPembayaran = $data['nomorNotaDinasPembayaran'];

            $parts = explode('-', $namaVendor);

            if (count($parts) > 1) {
                $result = trim($parts[0]);
                $namaVendor = $result;
            }

            $this->addNewVendor($namaVendor, $bankPenerima, $nomorRekening);

            $vendor = Vendor::where('account_number', $data['nomorRekening'])
                ->get()
                ->first();

            if ($vendor != null) {
                DocumentAuthorizationLetter::create([
                    'title' => $namaSurat,
                    'number' => $nomorSurat,
                    'contract_number' => $nomorKontrak,
                    'payment_total' => $jumlahPembayaran,
                    'created_by' => $user->name,
                    'vendor_name' => $namaVendor,
                    'bank_name' => $bankPenerima,
                    'account_number' => $nomorRekening,
                    'work_unit' => $unitKerja,
                    'vendor_id' => $vendor->id,
                    'payment_number' => $nomorNotaDinasPembayaran,
                ]);
            } else {
                DocumentAuthorizationLetter::create([
                    'title' => $namaSurat,
                    'number' => $nomorSurat,
                    'contract_number' => $nomorKontrak,
                    'payment_total' => $jumlahPembayaran,
                    'created_by' => $user->name,
                    'vendor_name' => $namaVendor,
                    'bank_name' => $bankPenerima,
                    'account_number' => $nomorRekening,
                    'work_unit' => $unitKerja,
                    'vendor_id' => null,
                    'payment_number' => $nomorNotaDinasPembayaran,
                ]);
            }

            return redirect()->route('documentauthorizationletter')->with('success', 'Berhasil menambahkan kebenaran dokumen baru');
        } catch (\Exception $e) {
            File::deleteDirectory('storage/files/kebenaran-dokumen/' . $user->email);
            if ($e->getCode() == 267) {
                return redirect()->back()->with('error', 'Versi PDF tidak sesuai, silahkan ubah versi PDF');
            } elseif ($e->getCode() == 268) {
                return redirect()->back()->with('error', 'Dokumen PDF memiliki enkripsi');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    private function mergePDF($fileSurat)
    {
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF($fileSurat, 'all');
        $user = Auth::user();
        $directoryPath = 'storage/files/kebenaran-dokumen/' . $user->email;
        if (File::exists($directoryPath)) {
            $files = File::allFiles($directoryPath);
            foreach ($files as $item) {
                $pdfMerger->addPDF($item, 'all');
            }
        }
        $pdfMerger->merge();
        $pdfMerger->save($fileSurat);
        File::deleteDirectory($directoryPath);
        return true;
    }

    private function wordTemplate(string $unitKerja, string $nomorSurat, string $tanggal, string $namaSurat, string $nomorKontrak, string $namaVendor, string $jumlahPembayaran, string $bankPenerima, string $nomorRekening, array $time)
    {
        $templateProcessor = new TemplateProcessor('template-' . $unitKerja . '.docx');
        $templateProcessor->setValues([
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => $tanggal,
            'namaSurat' => $namaSurat,
            'nomorKontrak' => $nomorKontrak,
            'namaVendor' => $namaVendor,
            'jumlahPembayaran' => $jumlahPembayaran,
            'bankPenerima' => $bankPenerima,
            'nomorRekening' => $nomorRekening,
        ]);

        $pathToSave = public_path('\storage\files\kebenaran-dokumen\\' . $time['sec'] . '-' . str_replace('/', '', $namaSurat) . '.docx');
        $templateProcessor->saveAs($pathToSave);
    }

    private function convertToPDF(string $namaSurat, string $time)
    {
        $iLovePdf = new Ilovepdf(config('services.api.pubkey'), config('services.api.secretkey'));
        $taskConvert = $iLovePdf->newTask('officepdf');
        $path = public_path('\storage\files\kebenaran-dokumen\\' . $time . '-' . str_replace('/', '', $namaSurat) . '.docx');
        $file = $taskConvert->addFile($path);
        $taskConvert->execute();
        $taskConvert->download(public_path('\storage\files\kebenaran-dokumen\\'));
        if (File::exists('storage/files/kebenaran-dokumen/' . $time . '-' . str_replace('/', '', $namaSurat) . '.docx')) {
            File::delete('storage/files/kebenaran-dokumen/' . $time . '-' . str_replace('/', '', $namaSurat) . '.docx');
        }
    }

    public function showDetailPage(DocumentAuthorizationLetter $documentAuthorizationLetter)
    {
        return view('pages.documentauthorizationletter.detaildocumentauthorizationletter', [
            'documentAuthorizationLetter' => $documentAuthorizationLetter,
        ]);
    }

    public function showEditPage(DocumentAuthorizationLetter $documentAuthorizationLetter)
    {
        $vendors = Vendor::all();
        $vendor = Vendor::find($documentAuthorizationLetter->vendor_id);

        return view('pages.documentauthorizationletter.editdocumentauthorizationletter', [
            'documentAuthorizationLetter' => $documentAuthorizationLetter,
            'vendors' => $vendors,
            'vendor' => $vendor,
        ]);
    }

    public function edit(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'mimes' => ':attribute harus berekstensi pdf',
            'unique' => ':attribute yang diinput sudah terdaftar',
        ];

        $this->validate(
            $request,
            [
                'nomorSurat' => 'required',
                'namaSurat' => 'required|min:10',
                'tanggalPembuatan' => 'required',
                'nomorKontrak' => 'required',
                'namaVendor' => 'required',
                'jumlahPembayaran' => 'required',
                'bankPenerima' => 'required',
                'nomorRekening' => 'required',
            ],
            $messages,
        );
        $user = Auth::user();
        $locale = 'id_ID';
        $date = new DateTime($request->tanggalPembuatan);
        $dateFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);

        try {
            $nomorSurat = $request->nomorSurat;
            $tanggal = $dateFormatter->format($date);
            $namaSurat = $request->namaSurat;
            $nomorKontrak = $request->nomorKontrak;
            $jumlahPembayaran = str_replace(',', '.', $request->jumlahPembayaran);
            $bankPenerima = $request->bankPenerima;
            $nomorRekening = $request->nomorRekening;
            $namaVendor = $request->namaVendor;
            $namaVendor = strtoupper($namaVendor);
            $unitKerja = $user->role == 1 ? $request->unitKerja : $user->work_unit;
            $paymentNumber = $request->nomorNotaDinasPembayaran;

            $parts = explode('-', $namaVendor);

            $time = gettimeofday();

            if (count($parts) > 1) {
                $result = trim($parts[0]);
                $namaVendor = $result;
            }

            $documentAuthorizationLetter = DocumentAuthorizationLetter::where('id', $request->id)
                ->get()
                ->first();

            $vendor = Vendor::where('account_number', $request->nomorRekening)
                ->get()
                ->first();

            if ($vendor != null) {
                $documentAuthorizationLetter->update([
                    'title' => $namaSurat,
                    'number' => $nomorSurat,
                    'contract_number' => $nomorKontrak,
                    'payment_total' => $jumlahPembayaran,
                    'created_by' => $user->name,
                    'bank_name' => $bankPenerima,
                    'account_number' => $nomorRekening,
                    'vendor_id' => $vendor->id,
                    'work_unit' => $unitKerja,
                    'payment_number' => $paymentNumber,
                    'created_at' => $date,
                ]);
            } else {
                $documentAuthorizationLetter->update([
                    'title' => $namaSurat,
                    'number' => $nomorSurat,
                    'contract_number' => $nomorKontrak,
                    'payment_total' => $jumlahPembayaran,
                    'created_by' => $user->name,
                    'bank_name' => $bankPenerima,
                    'account_number' => $nomorRekening,
                    'vendor_id' => null,
                    'work_unit' => $unitKerja,
                    'payment_number' => $paymentNumber,
                    'created_at' => $date,
                ]);
            }

            return redirect()->route('documentauthorizationletter')->with('success', 'Berhasil mengubah kebenaran dokumen');
        } catch (\Exception $e) {
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . str_replace('/', '', $namaSurat) . '.docx')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . str_replace('/', '', $namaSurat) . '.docx');
            }
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . str_replace('/', '', $namaSurat) . '.pdf')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . str_replace('/', '', $namaSurat) . '.pdf');
            }
            File::deleteDirectory('storage/files/kebenaran-dokumen/' . $user->email);
            dd($e);
            if ($e->getCode() == 267) {
                return redirect()->back()->with('error', 'Versi PDF tidak sesuai, silahkan ubah versi PDF');
            } elseif ($e->getCode() == 268) {
                return redirect()->back()->with('error', 'Dokumen PDF memiliki enkripsi');
            }
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function delete(string $id)
    {
        $documentAuthorizationLetter = DocumentAuthorizationLetter::find($id);

        if (File::exists('storage/files/kebenaran-dokumen/' . $documentAuthorizationLetter->file_path)) {
            File::delete('storage/files/kebenaran-dokumen/' . $documentAuthorizationLetter->file_path);
        }

        $documentAuthorizationLetter->delete();
        if ($documentAuthorizationLetter) {
            return redirect()->route('documentauthorizationletter')->with('success', 'Berhasil menghapus kebenaran dokumen');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus kebenaran dokumen');
        }
    }

    public function search(Request $request)
    {
        $workUnit = Auth::user()->work_unit;
        $keyword = $request->keyword;

        if ($workUnit === '') {
            return response()->json(['documentAuthorizationLetters' => []]);
        }

        $documentAuthorizationLetters = DocumentAuthorizationLetter::when(auth()->user()->role == 1, function ($q) use ($workUnit, $keyword) {
            $q->where('number', 'LIKE', '%' . $keyword . '%')->orWhere('title', 'LIKE', '%' . $keyword . '%');
        })
            ->when(auth()->user()->role != 1, function ($q) use ($keyword, $workUnit) {
                $q->where('work_unit', $workUnit)->where(function ($query) use ($keyword) {
                    $query->where('number', 'LIKE', '%' . $keyword . '%')->orWhere('title', 'LIKE', '%' . $keyword . '%');
                });
            })
            ->orderBy('created_at', 'DESC')
            ->get();

        return response()->json([
            'documentAuthorizationLetters' => $documentAuthorizationLetters,
        ]);
    }

    private function addNewVendor(string $name, string $bankName, string $accountNumber)
    {
        $vendor = DocumentAuthorizationLetter::all()->where('account_number', $accountNumber);
        if (count($vendor) >= 3 && !Vendor::where('account_number', $accountNumber)->exists()) {
            Vendor::create([
                'name' => $name,
                'bank_name' => $bankName,
                'account_number' => $accountNumber,
            ]);
        }
    }

    public function uploads(Request $request)
    {
        $files = $request->file('fileLampiran');
        $user = Auth::user();
        foreach ($files as $item) {
            $item->storeAs('public/files/kebenaran-dokumen/' . $user->email, $item->getClientOriginalName());
            return $item->getClientOriginalName();
        }
    }

    public function firstPage(DocumentAuthorizationLetter $documentAuthorizationLetter)
    {
        $fpdi = new Fpdi();
        $fpdi->AddPage();
        $fpdi->setSourceFile('storage/files/kebenaran-dokumen/' . $documentAuthorizationLetter->file_path);
        $fpdi->useTemplate($fpdi->importPage(1));

        $fpdi->Output($documentAuthorizationLetter->contract_number . ' KD.pdf', 'I');
    }

    public function deleteTmp(Request $request)
    {
        $fileName = $request->getContent();
        $user = Auth::user();
        if (File::exists('storage/files/kebenaran-dokumen/' . $user->email . '/' . $fileName)) {
            File::delete('storage/files/kebenaran-dokumen/' . $user->email . '/' . $fileName);
        }
    }

    public function print(DocumentAuthorizationLetter $documentAuthorizationLetter)
    {
        $locale = 'id_ID';
        $dateFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);

        $unitKerja = $documentAuthorizationLetter->work_unit;
        $nomorSurat = $documentAuthorizationLetter->number;
        $tanggal = $dateFormatter->format($documentAuthorizationLetter->created_at);
        $namaSurat = $documentAuthorizationLetter->title;
        $nomorKontrak = $documentAuthorizationLetter->contract_number;
        $namaVendor = $documentAuthorizationLetter->vendor_name;
        $jumlahPembayaran = $documentAuthorizationLetter->payment_total;
        $bankPenerima = $documentAuthorizationLetter->bank_name;
        $nomorRekening = $documentAuthorizationLetter->account_number;

        $templateProcessor = new TemplateProcessor('template-' . $unitKerja . '.docx');
        $templateProcessor->setValues([
            'nomorSurat' => $nomorSurat,
            'tanggalSurat' => $tanggal,
            'namaSurat' => $namaSurat,
            'nomorKontrak' => $nomorKontrak,
            'namaVendor' => $namaVendor,
            'jumlahPembayaran' => $jumlahPembayaran,
            'bankPenerima' => $bankPenerima,
            'nomorRekening' => $nomorRekening,
        ]);

        $fileName = $namaSurat . '.docx';
        $templateProcessor->saveAs($fileName);

        return response()->download($fileName)->deleteFileAfterSend(true);
    }
}
