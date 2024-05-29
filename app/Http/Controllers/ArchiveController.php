<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ArchiveController extends Controller
{
    public function index()
    {
        return view('pages.archive.archives', [
            'archives' => [],
        ]);
    }

    public function showCreatePage()
    {
        return view('pages.archive.createarchive');
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
            'fileArsip' => 'required|mimes:doc,pdf,docx,zip|max:5120',
        ], $messages);

        try {
            $user = Auth::user();
            $file = $request->file('fileArsip');
            $name = hash('sha256', $file->getClientOriginalName());

            $filepath = $request->file('fileArsip')->storeAs('files/arsip', $name . '.pdf', 'public');
            Archive::create([
                'title' => $request->namaSurat,
                'number' => $request->nomorSurat,
                'created_by' => $user->name,
                'file_path' => $filepath,
                'work_unit' => $user->role == 1 ? $request->unitKerja : $user->work_unit,
                'created_at' => $request->tanggalPembuatan,
            ]);

            return redirect()->route('archive')->with('success', 'Berhasil menambahkan arsip baru');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan arsip baru');
        }
    }

    public function showDetailPage(Archive $archive)
    {
        return view('pages.archive.detailarchive', [
            'archive' => $archive,
        ]);
    }

    public function delete(String $id)
    {
        $archive = Archive::find($id);

        if (File::exists('storage/' . $archive->file_path)) {
            File::delete('storage/' . $archive->file_path);
        }

        $archive->delete();
        if ($archive) {
            return redirect()->route('archive')->with('success', 'Berhasil menghapus arsip');
        } else {
            return redirect()->back()->with('error', 'Gagal menghapus arsip');
        }
    }

    public function showEditPage(Archive $archive)
    {
        return view('pages.archive.editarchive', [
            'archive' => $archive,
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
            'fileArsip' => 'mimes:doc,pdf,docx,zip|max:5120',
            'tanggalPembuatan' => 'required',
            'nomorSurat' => 'required',
        ], $messages);

        $archive = Archive::where('id', $request->id)->first();
        $user = Auth::user();
        $date = new Carbon($request->tanggalPembuatan);

        try {
            if ($request->hasFile('fileArsip')) {
                $file = $request->file('fileArsip');
                $name = hash('sha256', $file->getClientOriginalName());

                $oldFilePath = $archive->file_path;

                if (File::exists('storage/' . $oldFilePath)) {
                    File::delete('storage/' . $oldFilePath);
                }

                $filepath = $request->file('fileArsip')->storeAs('files/arsip', $name . '.pdf', 'public');

                $archive->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'file_path' => $filepath,
                    'number' => $request->nomorSurat,
                    'work_unit' => $user->role == 1 ? $request->unitKerja : $user->work_unit,
                    'created_at' => $date,
                ]);
            } else {
                $archive->update([
                    'title' => $request->namaSurat,
                    'created_by' => $user->name,
                    'number' => $request->nomorSurat,
                    'work_unit' => $user->role == 1 ? $request->unitKerja : $user->work_unit,
                    'created_at' => $date,
                ]);
            }

            if ($archive) {
                return redirect()->route('archive')->with('success', 'Berhasil ubah arsip');
            } else {
                return redirect()->back()->with('error', 'Gagal ubah arsip');
            }
        } catch (\Illuminate\Database\QueryException $e) {
            $errorCode = $e->errorInfo[1];
            if ($errorCode == 1062) {
                redirect()->back()->with('error', 'Nomor arsip telah digunakan.');
            }
            return redirect()->back()->with('error', 'Nomor arsip telah digunakan.');
        }
    }

    public function search(Request $request)
    {
        $workUnit = Auth::user()->work_unit;
        $keyword = $request->keyword;

        if ($workUnit === '') {
            return response()->json(['news' => []]);
        }

        $archives = Archive::when(auth()->user()->role == 1, function ($q) use ($keyword) {
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
            'archives' => $archives,
        ]);
    }
}
