<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\DocumentAuthorizationLetter;
use App\Models\News;
use App\Models\OfficialMemo;
use App\Models\OutgoingMail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use ZipArchive;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $officialMemo = OfficialMemo::all();
        $documentAuthorizationLetter = DocumentAuthorizationLetter::all();
        $news = News::all();
        $outgoingMail = OutgoingMail::all();
        $archive = Archive::all();

        return view('pages.home', [
            'officialMemoTotal' => count($officialMemo),
            'documentAuthorizationLetterTotal' => count($documentAuthorizationLetter),
            'newsTotal' => count($news),
            'outgoingMailTotal' => count($outgoingMail),
            'archiveTotal' => count($archive),
        ]);
    }

    public function logout()
    {
        return view('auth.login');
    }

    public function recapDocumentAuthorizationLetter(Request $request)
    {
        $from = date($request->tanggalAwal);
        $to = date($request->tanggalAkhir);
        $zip = new ZipArchive;
        $zipFileName = "rekapKD.zip";

        $data = DocumentAuthorizationLetter::whereBetween('created_at', [$from, $to])->get();
        if (count($data) != 0) {
            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                foreach ($data as $item) {
                    $path = 'storage/files/kebenaran-dokumen/' . $item->file_path;
                    $zip->addFile($path, basename($path));
                }

                $zip->close();

                return response()->download($zipFileName)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat zip file');
            }
        }
        return redirect()->back()->with('error', 'Tidak ada data');
    }

    public function recapOfficialMemo(Request $request)
    {
        $from = Carbon::createFromFormat('Y-m-d', $request->tanggalAwal);
        $to = Carbon::createFromFormat('Y-m-d', $request->tanggalAkhir);
        $zip = new ZipArchive;
        $zipFileName = "rekapNotaDinas.zip";

        $data = OfficialMemo::query()->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->get();
        if (count($data) != 0) {
            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                foreach ($data as $item) {
                    $path = 'storage/' . $item->file_path;
                    $zip->addFile($path, basename($path));
                }

                $zip->close();

                return response()->download($zipFileName)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat zip file');
            }
        }
        return redirect()->back()->with('error', 'Tidak ada data');
    }

    public function recapNews(Request $request)
    {
        $from = date($request->tanggalAwal);
        $to = date($request->tanggalAkhir);
        $zip = new ZipArchive;
        $zipFileName = "rekapBeritaAcara.zip";

        $data = News::query()->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->get();
        if (count($data) != 0) {
            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                foreach ($data as $item) {
                    $path = 'storage/' . $item->file_path;
                    $zip->addFile($path, basename($path));
                }

                $zip->close();

                return response()->download($zipFileName)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat zip file');
            }
        }
        return redirect()->back()->with('error', 'Tidak ada data');
    }

    public function recapOutgoingMail(Request $request)
    {
        $from = date($request->tanggalAwal);
        $to = date($request->tanggalAkhir);
        $zip = new ZipArchive;
        $zipFileName = "rekapSuratKeluar.zip";

        $data = OutgoingMail::query()->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->get();
        if (count($data) != 0) {
            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                foreach ($data as $item) {
                    $path = 'storage/' . $item->file_path;
                    $zip->addFile($path, basename($path));
                }

                $zip->close();

                return response()->download($zipFileName)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat zip file');
            }
        }
        return redirect()->back()->with('error', 'Tidak ada data');
    }

    public function recapArchive(Request $request)
    {
        $from = date($request->tanggalAwal);
        $to = date($request->tanggalAkhir);
        $zip = new ZipArchive;
        $zipFileName = "rekapArsip.zip";

        $data = Archive::query()->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->get();
        if (count($data) != 0) {
            if ($zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
                foreach ($data as $item) {
                    $path = 'storage/' . $item->file_path;
                    $zip->addFile($path, basename($path));
                }

                $zip->close();

                return response()->download($zipFileName)->deleteFileAfterSend(true);
            } else {
                return redirect()->back()->with('error', 'Gagal membuat zip file');
            }
        }
        return redirect()->back()->with('error', 'Tidak ada data');
    }
}
