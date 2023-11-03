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
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\TemplateProcessor;

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
        $newLetterNumber = $this->documentAuthorizationLetterNumber();
        $month = $this->numberToRomanRepresentation(date('n'));

        $template = sprintf("%s/WIL4/KD/%s/%s", $newLetterNumber, $month, date('Y')); // Format penomoran surat. Jangan ubah yang ada %s
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
            // 'fileLampiran' => 'required|mimes:doc,pdf,docx,zip'
        ], $messages);

        try {
            $user = Auth::user();
            $locale = 'id_ID';
            $date = new DateTime($request->tanggalPembuatan);
            $dateFormatter = new IntlDateFormatter($locale, IntlDateFormatter::LONG, IntlDateFormatter::NONE);

            $nomorSurat = $request->nomorSurat;
            $tanggal = $dateFormatter->format($date);
            $namaSurat = $request->namaSurat;
            $nomorKontrak = $request->nomorKontrak;
            $jumlahPembayaran = str_replace(',', '.', $request->jumlahPembayaran);
            $bankPenerima = $request->bankPenerima;
            $nomorRekening = $request->nomorRekening;
            $namaVendor = $request->namaVendor;

            $parts = explode('-', $namaVendor);

            if (count($parts) > 1) {
                $result = trim($parts[0]);
                $namaVendor = $result;
            }

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

            $pathToSave = public_path('\storage\files\kebenaran-dokumen\\' . $namaSurat . '.docx');
            $templateProcessor->saveAs($pathToSave);
            $this->convertToPDF($namaSurat);
            $fileName = $namaSurat . '.pdf';

            $vendor = Vendor::where('account_number', $request->nomorRekening)->get()->first();

            DocumentAuthorizationLetter::create([
                'title' => $namaSurat,
                'number' => $nomorSurat,
                'contract_number' => $nomorKontrak,
                'payment_total' => $jumlahPembayaran,
                'created_by' => $user->name,
                'vendor_id' => $vendor->id,
                'file_path' => $fileName,
            ]);

            return redirect()->route('home')->with('success', 'Berhasil menambahkan kebenaran dokumen baru');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e);
        }
    }

    public function convertToPDF(String $namaSurat)
    {
        $iLovePdf = new Ilovepdf(config('services.api.pubkey'), config('services.api.secretkey'));
        $taskConvert = $iLovePdf->newTask('officepdf');
        $path = public_path('\storage\files\kebenaran-dokumen\\' . $namaSurat . '.docx');
        $file = $taskConvert->addFile($path);
        $taskConvert->execute();
        $taskConvert->download(public_path('\storage\files\kebenaran-dokumen\\'));
    }

    public function showDetailPage(DocumentAuthorizationLetter $documentAuthorizationLetter)
    {
        return view('pages.documentauthorizationletter.detaildocumentauthorizationletter', [
            'documentAuthorizationLetter' => $documentAuthorizationLetter,
        ]);
    }
}
