<?php

namespace App\Http\Controllers;

use App\Models\Agreement;
use App\Models\DocumentAuthorizationLetter;
use App\Models\News;
use App\Models\OfficialMemo;
use App\Models\OutgoingMail;
use Carbon\Carbon;

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

        return view('pages.home', [
            'officialMemoTotal' => count($officialMemo),
            'documentAuthorizationLetterTotal' => count($documentAuthorizationLetter),
            'newsTotal' => count($news),
            'outgoingMailTotal' => count($outgoingMail),
        ]);
    }

    public function logout()
    {
        return view('auth.login');
    }
}
