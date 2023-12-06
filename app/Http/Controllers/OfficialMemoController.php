<?php

namespace App\Http\Controllers;

use App\Models\OfficialMemo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class OfficialMemoController extends Controller
{
    public function index()
    {
        $officialMemos = OfficialMemo::orderBy('created_at', 'DESC')->get();

        return view('pages.officialmemo.officialmemos', [
            'officialMemos' => $officialMemos,
        ]);
    }

    public function showCreatePage()
    {
        return view('pages.officialmemo.createofficialmemo', [
            'officialMemoNumber' => $this->officialMemoNumbering(),
        ]);
    }

    public function create(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'mimes' => ':attribute harus berekstensi pdf',
            'unique' => ':attribute yang diinput sudah terdaftar',
            'max' => ':attribute maximal 5 Mb'
        ];

        $this->validate($request, [
            'nomorSurat' => 'required|unique:official_memos,number',
            'namaSurat' => 'required|min:10',
            'fileNotaDinas' => 'required|mimes:doc,pdf,docx,zip|max:5120',
        ], $messages);

        try {
            $user = Auth::user();
            $file = $request->file('fileNotaDinas');
            $name = hash('sha256', $file->getClientOriginalName());

            $filepath = $request->file('fileNotaDinas')->storeAs('files/nota-dinas', $name . '.pdf', 'public');
            OfficialMemo::create([
                'title' => $request->namaSurat,
                'number' => $request->nomorSurat,
                'created_by' => $user->name,
                'file_path' => $filepath,
                'created_at' => $request->tanggalPembuatan,
            ]);

            return redirect()->route('officialmemo')->with('success', 'Berhasil menambahkan nota dinas baru');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan nota dinas baru');
        }
    }

    public function showDetailPage(OfficialMemo $officialMemo)
    {
        return view('pages.officialmemo.detailofficialmemo', [
            'officialMemo' => $officialMemo,
        ]);
    }

    public function delete(String $id)
    {
        $officialMemo = OfficialMemo::find($id);

        if (File::exists('storage/' . $officialMemo->file_path)) {
            File::delete('storage/' . $officialMemo->file_path);
        }

        $officialMemo->delete();
        if ($officialMemo) {
            return redirect()->route('officialmemo')->with('success', 'Berhasil menghapus nota dinas');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus nota dinas');
        }
    }

    public function officialMemoNumbering()
    {
        $newLetterNumber = $this->newOfficialMemoNumber();
        $month = $this->numberToRomanRepresentation(date('n'));

        $template = sprintf("%s/WIL4/ND/%s/%s", $newLetterNumber, $month, date('Y')); // Format penomoran surat. Jangan ubah yang ada %s
        return $template;
    }

    public function newOfficialMemoNumber()
    {
        $lastRecordData = OfficialMemo::latest()->first();

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

    public function showEditPage(OfficialMemo $officialMemo)
    {
        return view('pages.officialmemo.editofficialmemo', [
            'officialMemo' => $officialMemo,
        ]);
    }

    public function edit(Request $request)
    {
        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'mimes' => ':attribute harus berekstensi pdf',
            'max' => ':attribute maximal 5 Mb'
        ];

        $this->validate($request, [
            'namaSurat' => 'required|min:10',
            'fileNotaDinas' => 'mimes:doc,pdf,docx,zip|max:5120',
            'tanggalPembuatan' => 'required',
            'nomorSurat' => 'required',
        ], $messages);

        $officialMemo = OfficialMemo::where('id', $request->id)->first();
        $user = Auth::user();
        $date = new Carbon($request->tanggalPembuatan);

        try {
            if ($request->hasFile('fileNotaDinas')) {
                $file = $request->file('fileNotaDinas');
                $name = hash('sha256', $file->getClientOriginalName());

                $oldFilePath = $officialMemo->file_path;

                if (File::exists('storage/' . $oldFilePath)) {
                    File::delete('storage/' . $oldFilePath);
                }

                $filepath = $request->file('fileNotaDinas')->storeAs('files/nota-dinas', $name . '.pdf', 'public');

                // $checkFile = OfficialMemo::where('file_path', '=', $filepath);

                $officialMemo->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'file_path' => $filepath,
                    'number' => $request->nomorSurat,
                    'created_at' => $date,
                ]);
            } else {
                $officialMemo->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'number' => $request->nomorSurat,
                    'created_at' => $date,
                ]);
            }

            if ($officialMemo) {
                return redirect()->route('officialmemo')->with('success', 'Berhasil ubah nota dinas');
            } else {
                return redirect()->back()->with('error', 'Gagal ubah nota dinas');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                redirect()->back()->with('error', 'Nomor nota dinas telah digunakan.');
            }
            return redirect()->back()->with('error', 'Nomor nota dinas telah digunakan.');
        }
    }

    public function officialMemoNumberingLive(Request $request)
    {
        $date = strtotime($request->dateData);
        $unitKerja = $request->unitKerjaData;

        $records = OfficialMemo::whereMonth('created_at', date('m', $date))->where('number', 'LIKE', '%' . $unitKerja . '%')->orderBy('number', 'DESC')->get();

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
            $template = sprintf("%s/WIL4/ND/%s/%s", $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        } else {
            $template = sprintf("%s/WIL4-%s/ND/%s/%s", $newLetterNumber, strtoupper($unitKerja), $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        }

        return response()->json([
            'officialMemoNumber' => $template,
        ]);
    }

    public function search(Request $request)
    {
        $officialMemos = OfficialMemo::orderBy('created_at', 'DESC')->get();
        if ($request->keyword != '') {
            $officialMemos = OfficialMemo::query()
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->keyword . '%');
                })
                ->orWhere(function ($query) use ($request) {
                    $query->where('number', 'LIKE', '%' . $request->keyword . '%');
                })
                ->get();
        }
        return response()->json([
            'officialMemos' => $officialMemos,
        ]);
    }

    // public function 
}
