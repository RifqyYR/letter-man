<?php

namespace App\Http\Controllers;

use App\Models\OfficialMemo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class OfficialMemoController extends Controller
{
    public function index()
    {
        $officialMemos = OfficialMemo::all();

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
        $this->officialMemoNumbering();

        $messages = [
            'required' => ':attribute tidak boleh kosong',
            'min' => ':attribute minimal :min karakter',
            'mimes' => ':attribute harus berekstensi pdf',
            'unique' => ':attribute yang diinput sudah terdaftar',
        ];

        $this->validate($request, [
            'nomorSurat' => 'required|unique:official_memos,number',
            'namaSurat' => 'required|min:10',
            'fileNotaDinas' => 'required|mimes:doc,pdf,docx,zip'
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
                'file_path' => $filepath
            ]);

            return redirect()->route('home')->with('success', 'Berhasil menambahkan nota dinas baru');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan nota dinas baru');
        }
    }

    public function showDetail(OfficialMemo $officialMemo)
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
            return redirect()->route('home')->with('success', 'Berhasil menghapus not dinas');
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
}
