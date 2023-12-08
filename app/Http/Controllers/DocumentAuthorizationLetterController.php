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
        $unitKerja = $request->unitKerjaData;

        $records = DocumentAuthorizationLetter::whereMonth('created_at', date('m', $date))->where('number', 'LIKE', '%' . $unitKerja . '%')->orderBy('number', 'DESC')->get();

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

        if ($unitKerja == 'wil4') {
            $template = sprintf("%s/WIL4/KD/%s/%s", $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        } else {
            $template = sprintf("%s/WIL4-%s/KD/%s/%s", $newLetterNumber, strtoupper($unitKerja), $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        }

        return response()->json([
            'documentAuthorizationLetterNumber' => $template,
        ]);
    }

    public function create(Request $request)
    {
        $data = $request->all();
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

            $parts = explode('-', $namaVendor);

            $time = gettimeofday();

            if (count($parts) > 1) {
                $result = trim($parts[0]);
                $namaVendor = $result;
            }

            $this->addNewVendor($namaVendor, $bankPenerima, $nomorRekening);

            $this->wordTemplate($nomorSurat, $tanggal, $namaSurat, $nomorKontrak, $namaVendor, $jumlahPembayaran, $bankPenerima, $nomorRekening, $time);

            $this->convertToPDF($namaSurat, $time['sec']);

            $fileName = $time['sec'] . '-' . $namaSurat . '.pdf';

            $mergeRes = $this->mergePDF(public_path('\storage\files\kebenaran-dokumen\\' . $fileName));

            if ($mergeRes) {
                $vendor = Vendor::where('account_number', $data['nomorRekening'])->get()->first();

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

                return redirect()->route('documentauthorizationletter')->with('success', 'Berhasil menambahkan kebenaran dokumen baru');
            }
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.docx')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.docx');
            }
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.pdf')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.pdf');
            }

            redirect()->back()->with('error', $mergeRes);
        } catch (\Exception $e) {
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.docx')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.docx');
            }
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.pdf')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.pdf');
            }
            File::deleteDirectory('storage/files/kebenaran-dokumen/'.$user->email);
            if ($e->getCode() == 267) {
                return redirect()->back()->with('error', "Versi PDF tidak sesuai, silahkan ubah versi PDF");
            } else if ($e->getCode() == 268) {
                return redirect()->back()->with('error', "Dokumen PDF memiliki enkripsi");
            }
            return redirect()->back()->with('error', "Terjadi kesalahan");
        }
    }

    private function mergePDF($fileSurat)
    {
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF($fileSurat, 'all');
        $user = Auth::user();
        $files = File::allFiles('storage/files/kebenaran-dokumen/' . $user->email);
        foreach ($files as $item) {
            $pdfMerger->addPDF($item, 'all');
        }
        $pdfMerger->merge();
        $pdfMerger->save($fileSurat);
        File::deleteDirectory('storage/files/kebenaran-dokumen/' . $user->email);
        return true;
    }

    private function wordTemplate(String $nomorSurat, String $tanggal, String $namaSurat, String $nomorKontrak, String $namaVendor, String $jumlahPembayaran, String $bankPenerima, String $nomorRekening, array $time)
    {
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
    }

    private function wordTemplate2(String $nomorSurat, String $tanggal, String $namaSurat, String $nomorKontrak, String $namaVendor, String $jumlahPembayaran, String $bankPenerima, String $nomorRekening, array $time)
    {
        $templateProcessor = new TemplateProcessor('template-2.docx');
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
            $namaVendor = strtoupper($namaVendor);

            $parts = explode('-', $namaVendor);

            $time = gettimeofday();

            if (count($parts) > 1) {
                $result = trim($parts[0]);
                $namaVendor = $result;
            }

            $documentAuthorizationLetter = DocumentAuthorizationLetter::where('id', $request->id)->get()->first();

            $fileName = $time['sec'] . '-' . $namaSurat . '.pdf';
            $oldFileName = $documentAuthorizationLetter->file_path;
            $vendor = Vendor::where('account_number', $request->nomorRekening)->get()->first();

            $this->wordTemplate($nomorSurat, $tanggal, $namaSurat, $nomorKontrak, $namaVendor, $jumlahPembayaran, $bankPenerima, $nomorRekening, $time);

            $this->convertToPDF($namaSurat, $time['sec']);

            $mergeRes = $this->mergePDF(public_path('\storage\files\kebenaran-dokumen\\' . $fileName));

            if ($mergeRes) {
                if (File::exists('storage/files/kebenaran-dokumen/' . $oldFileName)) {
                    File::delete('storage/files/kebenaran-dokumen/' . $oldFileName);
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

                return redirect()->route('documentauthorizationletter')->with('success', 'Berhasil mengubah kebenaran dokumen');
            }

            return redirect()->back()->with('error', $mergeRes);
        } catch (\Exception $e) {
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.docx')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.docx');
            }
            if (File::exists('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.pdf')) {
                File::delete('storage/files/kebenaran-dokumen/' . $time['sec'] . '-' . $namaSurat . '.pdf');
            }
            File::deleteDirectory('storage/files/kebenaran-dokumen/' . $user->email);
            if ($e->getCode() == 267) {
                return redirect()->back()->with('error', "Versi PDF tidak sesuai, silahkan ubah versi PDF");
            } else if ($e->getCode() == 268) {
                return redirect()->back()->with('error', "Dokumen PDF memiliki enkripsi");
            }
            return redirect()->back()->with('error', "Terjadi kesalahan");
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
            return redirect()->route('documentauthorizationletter')->with('success', 'Berhasil menghapus kebenaran dokumen');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus kebenaran dokumen');
        }
    }

    public function search(Request $request)
    {
        $documentAuthorizationLetters = DocumentAuthorizationLetter::orderBy('created_at', 'DESC')->get();
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
}
