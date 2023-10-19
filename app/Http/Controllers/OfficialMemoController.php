<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OfficialMemoController extends Controller
{
    public function index()
    {
        return view('pages.officialmemo.officialmemos');
    }

    public function showCreatePage()
    {
        return view('pages.officialmemo.createofficialmemo');
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
            'nomorSurat' => 'required|unique:official_memos,number',
            'namaSurat' => 'required|min:10',
            'fileNotaDinas' => 'required|mimes:doc,pdf,docx,zip'
        ], $messages);
    }
}
