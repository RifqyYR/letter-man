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
        $news = [];

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
            'max' => ':attribute maximal 5 Mb',
        ];

        $this->validate(
            $request,
            [
                'nomorSurat' => 'required|unique:news,number',
                'namaSurat' => 'required|min:10',
                'fileBeritaAcara' => 'required|mimes:doc,pdf,docx,zip|max:5120',
            ],
            $messages,
        );

        try {
            $user = Auth::user();
            $file = $request->file('fileBeritaAcara');
            $name = hash('sha256', $file->getClientOriginalName());

            $filepath = $request->file('fileBeritaAcara')->storeAs('files/berita-acara', $name . '.pdf', 'public');
            News::create([
                'title' => $request->namaSurat,
                'number' => $request->nomorSurat,
                'created_by' => $user->name,
                'file_path' => $filepath,
                'work_unit' => $request->unitKerja,
                'created_at' => $request->tanggalPembuatan,
            ]);

            return redirect()
                ->route('news')
                ->with('success', 'Berhasil menambahkan berita acara baru');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan berita acara baru');
        }
    }

    public function showDetailPage(News $news)
    {
        return view('pages.news.detailnews', [
            'news' => $news,
        ]);
    }

    public function delete(string $id)
    {
        $news = News::find($id);

        if (File::exists('storage/' . $news->file_path)) {
            File::delete('storage/' . $news->file_path);
        }

        $news->delete();
        if ($news) {
            return redirect()
                ->route('news')
                ->with('success', 'Berhasil menghapus berita acara');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus berita acara');
        }
    }

    public function newsNumbering()
    {
        $newLetterNumber = $this->newNewsNumber();
        $month = $this->numberToRomanRepresentation(date('n'));

        $template = sprintf('%s/WIL4/BA/%s/%s', $newLetterNumber, $month, date('Y')); // Format penomoran surat. Jangan ubah yang ada %s
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
            'max' => ':attribute maximal 5 Mb',
        ];

        $this->validate(
            $request,
            [
                'namaSurat' => 'required|min:10',
                'fileBeritaAcara' => 'mimes:doc,pdf,docx,zip|max:5120',
                'tanggalPembuatan' => 'required',
                'nomorSurat' => 'required',
            ],
            $messages,
        );

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

                $news->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'file_path' => $filepath,
                    'number' => $request->nomorSurat,
                    'work_unit' => $request->unitKerja,
                    'created_at' => $date,
                ]);
            } else {
                $news->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'number' => $request->nomorSurat,
                    'work_unit' => $request->unitKerja,
                    'created_at' => $date,
                ]);
            }

            if ($news) {
                return redirect()
                    ->route('news')
                    ->with('success', 'Berhasil ubah berita acara');
            } else {
                return redirect()
                    ->back()
                    ->with('error', 'Gagal ubah berita acara');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                redirect()
                    ->back()
                    ->with('error', 'Nomor berita acara telah digunakan.');
            }
            return redirect()
                ->back()
                ->with('error', 'Nomor berita acara telah digunakan.');
        }
    }

    public function newsNumberingLive(Request $request)
    {
        $date = strtotime($request->dateData);
        $unitKerja = $request->unitKerjaData;

        $records = News::whereMonth('created_at', date('m', $date))
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
            $template = sprintf('%s/WIL4/BA/%s/%s', $newLetterNumber, $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        } else {
            $template = sprintf('%s/WIL4-%s/BA/%s/%s', $newLetterNumber, strtoupper($unitKerja), $month, date('Y', $date)); // Format penomoran surat. Jangan ubah yang ada %s
        }

        return response()->json([
            'newsNumber' => $template,
        ]);
    }

    public function search(Request $request)
    {
        $workUnit = Auth::user()->work_unit;
        $keyword = $request->keyword;

        if ($workUnit === '') {
            return response()->json(['news' => []]);
        }

        $news = News::when(auth()->user()->role == 1, function ($q) use ($keyword) {
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
            'news' => $news,
        ]);
    }
}
