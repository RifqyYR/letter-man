<?php

namespace App\Http\Controllers;

use App\Models\DocumentAuthorizationLetter;
use App\Models\Vendor;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Ilovepdf\Ilovepdf;
use IntlDateFormatter;
use Nette\Utils\FileSystem;
use PhpOffice\PhpWord\TemplateProcessor;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;

class DocumentAuthorizationLetterController extends Controller
{
    public function index()
    {
        $documentAuthorizationLetters = DocumentAuthorizationLetter::orderBy('created_at', 'DESC')->get();

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

        $records = DocumentAuthorizationLetter::whereMonth('created_at', date('m', $date))->orderBy('number', 'DESC')->get();

        if (count($records) != 0) {
            $lastRecordData = $records->first();
            $letterNumber = $lastRecordData->number;
            $letterNumber = (int)substr($letterNumber, 0, 3);
            $letterNumber += 1;
            $newLetterNumber = str_pad($letterNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $newLetterNumber = str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        $month = $this->numberToRomanRepresentation(date('n', $date));
        
        $template = sprintf("%s/WIL4/KD/%s/%s", $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        
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
                $letterNumber = (int)substr($letterNumber, 0, 3);
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
        $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
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

        $records = DocumentAuthorizationLetter::whereMonth('created_at', date('m', $date))->orderBy('number', 'DESC')->get();

        if (count($records) != 0) {
            $lastRecordData = $records->first();
            $letterNumber = $lastRecordData->number;
            $letterNumber = (int)substr($letterNumber, 0, 3);
            $letterNumber += 1;
            $newLetterNumber = str_pad($letterNumber, 3, '0', STR_PAD_LEFT);
        } else {
            $newLetterNumber = str_pad(1, 3, '0', STR_PAD_LEFT);
        }

        $month = $this->numberToRomanRepresentation(date('n', $date));

        $template = sprintf("%s/WIL4/KD/%s/%s", $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s

        return response()->json([
            'documentAuthorizationLetterNumber' => $template,
        ]);
    }

    public function create(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'mimes' => ':attribute harus berekstensi pdf',
            'unique' => ':attribute yang diinput sudah terdaftar',
        ];

        $this->validate($request, [
            'nomorSurat' => 'required|unique:document_authorization_letters,number',
            'namaSurat' => 'required|min:10',
            'tanggalPembuatan' => 'required',
            'nomorKontrak' => 'required',
            'namaVendor' => 'required',
            'jumlahPembayaran' => 'required',
            'bankPenerima' => 'required',
            'nomorRekening' => 'required',
            'fileLampiran' => 'required'
        ], $messages);
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
            $tujuan = $request->radioTemplate;
            $namaVendor = strtoupper($namaVendor);

            $parts = explode('-', $namaVendor);

            $time = gettimeofday();

            if (count($parts) > 1) {
                $result = trim($parts[0]);
                $namaVendor = $result;
            }

            $this->addNewVendor($namaVendor, $bankPenerima, $nomorRekening);

            $this->wordTemplate($nomorSurat, $tanggal, $namaSurat, $nomorKontrak, $namaVendor, $jumlahPembayaran, $bankPenerima, $nomorRekening, $tujuan, $time);

            $this->convertToPDF($namaSurat, $time['sec']);

            $fileName = $time['sec'] . '-' . $namaSurat . '.pdf';

            $this->mergePDF(public_path('\storage\files\kebenaran-dokumen\\' . $fileName));

            $vendor = Vendor::where('account_number', $request->nomorRekening)->get()->first();

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
                    'vendor_id' => $vendor->id,
                    'file_path' => $fileName,
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
                    'vendor_id' => null,
                    'file_path' => $fileName,
                ]);
            }

            return redirect()->route('home')->with('success', 'Berhasil menambahkan kebenaran dokumen baru');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e);
        }
    }

    private function mergePDF($fileSurat)
    {
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF($fileSurat, 'all');
        $files = File::allFiles('storage/files/kebenaran-dokumen/tmp');
        foreach ($files as $item) {
            $pdfMerger->addPDF($item, 'all');
        }
        $pdfMerger->merge();
        $pdfMerger->save($fileSurat);
        File::cleanDirectory('storage/files/kebenaran-dokumen/tmp');
    }

    private function wordTemplate(String $nomorSurat, String $tanggal, String $namaSurat, String $nomorKontrak, String $namaVendor, String $jumlahPembayaran, String $bankPenerima, String $nomorRekening, String $tujuan, array $time)
    {
        if ($tujuan == "PJM") {
            $templateProcessor = new TemplateProcessor('template.docx');
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

            $pathToSave = public_path('\storage\files\kebenaran-dokumen\\' . $time['sec'] . '-' . $namaSurat . '.docx');
            $templateProcessor->saveAs($pathToSave);
        } else {
            $templateProcessor = new TemplateProcessor('template-ho.docx');
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

            $pathToSave = public_path('\storage\files\kebenaran-dokumen\\' . $time['sec'] . '-' . $namaSurat . '.docx');
            $templateProcessor->saveAs($pathToSave);
        }
    }

    private function convertToPDF(String $namaSurat, String $time)
    {
        $iLovePdf = new Ilovepdf(config('services.api.pubkey'), config('services.api.secretkey'));
        $taskConvert = $iLovePdf->newTask('officepdf');
        $path = public_path('\storage\files\kebenaran-dokumen\\' . $time . '-' . $namaSurat . '.docx');
        $file = $taskConvert->addFile($path);
        $taskConvert->execute();
        $taskConvert->download(public_path('\storage\files\kebenaran-dokumen\\'));
        if (File::exists('storage/files/kebenaran-dokumen/' . $time . '-' . $namaSurat . '.docx')) {
            File::delete('storage/files/kebenaran-dokumen/' . $time . '-' . $namaSurat . '.docx');
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
            'vendor' => $vendor
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

        $this->validate($request, [
            'nomorSurat' => 'required',
            'namaSurat' => 'required|min:10',
            'tanggalPembuatan' => 'required',
            'nomorKontrak' => 'required',
            'namaVendor' => 'required',
            'jumlahPembayaran' => 'required',
            'bankPenerima' => 'required',
            'nomorRekening' => 'required',
            'fileLampiran' => 'required'
        ], $messages);
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
            $tujuan = $request->radioTemplate;
            $namaVendor = strtoupper($namaVendor);

            $parts = explode('-', $namaVendor);

            $time = gettimeofday();

            if (count($parts) > 1) {
                $result = trim($parts[0]);
                $namaVendor = $result;
            }

            $documentAuthorizationLetter = DocumentAuthorizationLetter::where('id', $request->id)->get()->first();

            $fileName = $time['sec'] . '-' . $namaSurat . '.pdf';
            $vendor = Vendor::where('account_number', $request->nomorRekening)->get()->first();

            $this->wordTemplate($nomorSurat, $tanggal, $namaSurat, $nomorKontrak, $namaVendor, $jumlahPembayaran, $bankPenerima, $nomorRekening, $tujuan, $time);

            $this->convertToPDF($namaSurat, $time['sec']);

            $this->mergePDF(public_path('\storage\files\kebenaran-dokumen\\' . $fileName));

            $oldPath = $documentAuthorizationLetter->file_path;

            if (File::exists('storage/files/kebenaran-dokumen/' . $oldPath)) {
                File::delete('storage/files/kebenaran-dokumen/' . $oldPath);
            }

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
                    'file_path' => $fileName,
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
                    'file_path' => $fileName,
                    'created_at' => $date,
                ]);
            }

            return redirect()->route('home')->with('success', 'Berhasil mengubah kebenaran dokumen');
        } catch (\Exception $e) {
            dd($e);
            return redirect()->back()->with('error', $e);
        }
    }

    public function delete(String $id)
    {
        $documentAuthorizationLetter = DocumentAuthorizationLetter::find($id);

        if (File::exists('storage/files/kebenaran-dokumen/' . $documentAuthorizationLetter->file_path)) {
            File::delete('storage/files/kebenaran-dokumen/' . $documentAuthorizationLetter->file_path);
        }

        $documentAuthorizationLetter->delete();
        if ($documentAuthorizationLetter) {
            return redirect()->route('home')->with('success', 'Berhasil menghapus kebenaran dokumen');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus kebenaran dokumen');
        }
    }

    public function search(Request $request)
    {
        $documentAuthorizationLetters = DocumentAuthorizationLetter::orderBy('number', 'DESC')->get();
        if ($request->keyword != '') {
            $documentAuthorizationLetters = DocumentAuthorizationLetter::query()
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->keyword . '%');
                })
                ->orWhere(function ($query) use ($request) {
                    $query->where('number', 'LIKE', '%' . $request->keyword . '%');
                })
                ->get();
        }
        return response()->json([
            'documentAuthorizationLetters' => $documentAuthorizationLetters,
        ]);
    }

    private function addNewVendor(String $name, String $bankName, String $accountNumber)
    {
        $vendor = DocumentAuthorizationLetter::all()->where('account_number', $accountNumber);;
        if (count($vendor) >= 3 && !Vendor::where('account_number', $accountNumber)->exists()) {
            Vendor::create([
                'name' => $name,
                'bank_name' => $bankName,
                'account_number' => $accountNumber
            ]);
        }
    }

    public function uploads(Request $request)
    {
        $files = $request->file('fileLampiran');
        foreach ($files as $item) {
            $item->storeAs('public/files/kebenaran-dokumen/tmp', $item->getClientOriginalName());
        }
    }
}
