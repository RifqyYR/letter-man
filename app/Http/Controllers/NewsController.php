<?php

namespace App\Http\Controllers;

use App\Models\News;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::orderBy('created_at', 'DESC')->get();

        return view('pages.news.news', [
            'news' => $news,
        ]);
    }

    public function showCreatePage()
    {
        return view('pages.news.createnews', [
            'newsNumber' => $this->newsNumbering(),
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
            'nomorSurat' => 'required|unique:news,number',
            'namaSurat' => 'required|min:10',
            'fileBeritaAcara' => 'required|mimes:doc,pdf,docx,zip'
        ], $messages);

        try {
            $user = Auth::user();
            $file = $request->file('fileBeritaAcara');
            $name = hash('sha256', $file->getClientOriginalName());

            $filepath = $request->file('fileBeritaAcara')->storeAs('files/berita-acara', $name . '.pdf', 'public');
            News::create([
                'title' => $request->namaSurat,
                'number' => $request->nomorSurat,
                'created_by' => $user->name,
                'file_path' => $filepath
            ]);

            return redirect()->route('home')->with('success', 'Berhasil menambahkan berita acara baru');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan berita acara baru');
        }
    }

    public function showDetailPage(News $news)
    {
        return view('pages.news.detailnews', [
            'news' => $news,
        ]);
    }

    public function delete(String $id)
    {
        $news = News::find($id);

        if (File::exists('storage/' . $news->file_path)) {
            File::delete('storage/' . $news->file_path);
        }

        $news->delete();
        if ($news) {
            return redirect()->route('home')->with('success', 'Berhasil menghapus berita acara');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus berita acara');
        }
    }

    public function newsNumbering()
    {
        $newLetterNumber = $this->newNewsNumber();
        $month = $this->numberToRomanRepresentation(date('n'));

        $template = sprintf("%s/WIL4/BA/%s/%s", $newLetterNumber, $month, date('Y')); // Format penomoran surat. Jangan ubah yang ada %s
        return $template;
    }

    public function newNewsNumber()
    {
        $lastRecordData = News::latest()->first();

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

    public function showEditPage(News $news)
    {
        return view('pages.news.editnews', [
            'news' => $news,
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
            'fileBeritaAcara' => 'mimes:doc,pdf,docx,zip',
            'tanggalPembuatan' => 'required',
            'nomorSurat' => 'required',
        ], $messages);

        $news = News::where('id', $request->id)->first();
        $user = Auth::user();
        $date = new Carbon($request->tanggalPembuatan);

        try {
            if ($request->hasFile('fileBeritaAcara')) {
                $file = $request->file('fileBeritaAcara');
                $name = hash('sha256', $file->getClientOriginalName());

                $oldFilePath = $news->file_path;

                if (File::exists('storage/' . $oldFilePath)) {
                    File::delete('storage/' . $oldFilePath);
                }

                $filepath = $request->file('fileBeritaAcara')->storeAs('files/berita-acara', $name . '.pdf', 'public');

                // $checkFile = news::where('file_path', '=', $filepath);

                $news->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'file_path' => $filepath,
                    'number' => $request->nomorSurat,
                    'created_at' => $date,
                ]);
            } else {
                $news->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'number' => $request->nomorSurat,
                    'created_at' => $date,
                ]);
            }

            if ($news) {
                return redirect()->route('home')->with('success', 'Berhasil ubah berita acara');
            } else {
                return redirect()->back()->with('error', 'Gagal ubah berita acara');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                redirect()->back()->with('error', 'Nomor berita acara telah digunakan.');
            }
            return redirect()->back()->with('error', 'Nomor berita acara telah digunakan.');
        }
    }

    public function newsNumberingLive(Request $request)
    {
        $date = strtotime($request->dateData);

        $records = News::whereMonth('created_at', date('m', $date))->orderBy('number', 'DESC')->get();

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

        $template = sprintf("%s/WIL4/BA/%s/%s", $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s

        return response()->json([
            'newsNumber' => $template,
        ]);
    }

    public function search(Request $request)
    {
        $news = News::all();
        if ($request->keyword != '') {
            $news = News::query()
                ->where(function ($query) use ($request) {
                    $query->where('title', 'LIKE', '%' . $request->keyword . '%');
                })
                ->orWhere(function ($query) use ($request) {
                    $query->where('number', 'LIKE', '%' . $request->keyword . '%');
                })
                ->get();
        }
        return response()->json([
            'news' => $news,
        ]);
    }
}
