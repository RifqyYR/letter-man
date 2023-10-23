@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="row">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Ubah Nota Dinas</h1>
            </div>
        </div>

        {{-- Input Form --}}
        <div class="card mt-2">
            <div class="card-body">
                <form action="/proses-ubah-nota-dinas" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="namaSurat">Judul</label>
                        <input type="text" class="form-control @error('namaSurat') is-invalid @enderror" name="namaSurat"
                            value="{{ $officialMemo->title }}">
                        @error('namaSurat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggalPembuatan">Tanggal Pembuatan</label>
                        <input type="date" id="tanggalPembuatan"
                            class="form-control @error('tanggalPembuatan') is-invalid @enderror" name="tanggalPembuatan"
                            autocomplete="off" value="{{ $officialMemo->created_at->toDateString() }}" />
                        @error('tanggalPembuatan')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nomorSurat">Nomor Surat</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('nomorSurat') is-invalid @enderror"
                                name="nomorSurat" id="nomorSurat" value="{{ $officialMemo->number }}" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-info" id="btnSetReadonly">Ubah Nomor</button>
                            </div>
                        </div>
                        @error('nomorSurat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fileNotaDinas">Upload Nota Dinas</label>
                        <input type="file" class="form-control-file @error('fileNotaDinas') is-invalid @enderror"
                            id="fileNotaDinas" name="fileNotaDinas" value="{{ $officialMemo->file_path }}">
                        @error('fileNotaDinas')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <input type="hidden" name="id" value="{{ $officialMemo->id }}">
                    <div class="form-group mt-5">
                        <input type="submit" class="btn btn-info" value="Ubah">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
