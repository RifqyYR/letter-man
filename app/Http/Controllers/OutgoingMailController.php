<?php

namespace App\Http\Controllers;

use App\Models\OutgoingMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class OutgoingMailController extends Controller
{
    public function index()
    {
        $outgoingmails = OutgoingMail::orderBy('created_at', 'DESC')->get();

        return view('pages.outgoingmail.outgoingmails', [
            'outgoingmails' => $outgoingmails,
        ]);
    }

    public function showCreatePage()
    {
        return view('pages.outgoingmail.createoutgoingmail', [
            'outgoingMailNumber' => $this->outgoingMailNumbering(),
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
            'nomorSurat' => 'required|unique:outgoing_mails,number',
            'namaSurat' => 'required|min:10',
            'fileSuratKeluar' => 'required|mimes:doc,pdf,docx,zip'
        ], $messages);

        try {
            $user = Auth::user();
            $file = $request->file('fileSuratKeluar');
            $name = hash('sha256', $file->getClientOriginalName());

            $filepath = $request->file('fileSuratKeluar')->storeAs('files/surat-keluar', $name . '.pdf', 'public');
            OutgoingMail::create([
                'title' => $request->namaSurat,
                'number' => $request->nomorSurat,
                'created_by' => $user->name,
                'file_path' => $filepath
            ]);

            return redirect()->route('home')->with('success', 'Berhasil menambahkan surat keluar baru');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan surat keluar baru');
        }
    }

    public function showDetailPage(OutgoingMail $outgoingmail)
    {
        return view('pages.outgoingmail.detailoutgoingmail', [
            'outgoingmail' => $outgoingmail,
        ]);
    }

    public function delete(String $id)
    {
        $outgoingmails = OutgoingMail::find($id);

        if (File::exists('storage/' . $outgoingmails->file_path)) {
            File::delete('storage/' . $outgoingmails->file_path);
        }

        $outgoingmails->delete();
        if ($outgoingmails) {
            return redirect()->route('home')->with('success', 'Berhasil menghapus surat keluar');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus surat keluar');
        }
    }

    public function outgoingMailNumbering()
    {
        $newLetterNumber = $this->newOutgoingMailNumber();
        $month = $this->numberToRomanRepresentation(date('n'));

        $template = sprintf("%s/WIL4/SK/%s/%s", $newLetterNumber, $month, date('Y')); // Format penomoran surat. Jangan ubah yang ada %s
        return $template;
    }

    public function newOutgoingMailNumber()
    {
        $lastRecordData = OutgoingMail::latest()->first();

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

    public function showEditPage(OutgoingMail $outgoingmail)
    {
        return view('pages.outgoingmail.editoutgoingmail', [
            'outgoingmail' => $outgoingmail,
        ]);
    }

    public function edit(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'mimes' => ':attribute harus berekstensi pdf',
        ];

        $this->validate($request, [
            'namaSurat' => 'required|min:10',
            'fileSuratKeluar' => 'mimes:doc,pdf,docx,zip',
            'tanggalPembuatan' => 'required',
            'nomorSurat' => 'required',
        ], $messages);

        $outgoingmails = OutgoingMail::where('id', $request->id)->first();
        $user = Auth::user();
        $date = new Carbon($request->tanggalPembuatan);

        try {
            if ($request->hasFile('fileSuratKeluar')) {
                $file = $request->file('fileSuratKeluar');
                $name = hash('sha256', $file->getClientOriginalName());

                $oldFilePath = $outgoingmails->file_path;

                if (File::exists('storage/' . $oldFilePath)) {
                    File::delete('storage/' . $oldFilePath);
                }

                $filepath = $request->file('fileSuratKeluar')->storeAs('files/surat-keluar', $name . '.pdf', 'public');

                // $checkFile = outgoingmails::where('file_path', '=', $filepath);

                $outgoingmails->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'file_path' => $filepath,
                    'number' => $request->nomorSurat,
                    'created_at' => $date,
                ]);
            } else {
                $outgoingmails->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'number' => $request->nomorSurat,
                    'created_at' => $date,
                ]);
            }

            if ($outgoingmails) {
                return redirect()->route('home')->with('success', 'Berhasil ubah surat keluar');
            } else {
                return redirect()->back()->with('error', 'Gagal ubah surat keluar');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                redirect()->back()->with('error', 'Nomor surat keluar telah digunakan.');
            }
            return redirect()->back()->with('error', 'Nomor surat keluar telah digunakan.');
        }
    }

    public function outgoingMailNumberingLive(Request $request)
    {
        $date = strtotime($request->dateData);

        $records = OutgoingMail::whereMonth('created_at', date('m', $date))->orderBy('number', 'DESC')->get();

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

        $template = sprintf("%s/WIL4/SK/%s/%s", $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s

        return response()->json([
            'outgoingMailNumber' => $template,
        ]);
    }

    public function search(Request $request)
    {
        $outgoingmails = OutgoingMail::orderBy('number', 'DESC')->get();
        if ($request->keyword != '') {
            $outgoingmails = OutgoingMail::query()
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->keyword . '%');
                })
                ->orWhere(function ($query) use ($request) {
                    $query->where('number', 'LIKE', '%' . $request->keyword . '%');
                })
                ->get();
        }
        return response()->json([
            'outgoingmails' => $outgoingmails,
        ]);
    }
}
