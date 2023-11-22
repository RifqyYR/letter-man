@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="row">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Ubah Surat Keluar</h1>
            </div>
        </div>

        {{-- Input Form --}}
        <div class="card mt-2">
            <div class="card-body">
                <form action="/proses-ubah-surat-keluar" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="unitKerja">Unit Kerja</label><br>
                        <select class="form-select" aria-label="Default select example" name="unitKerja" id="unitKerja">
                            <option value="wil4" selected>Wilayah 4</option>
                            <option value="kal1">Kalimantan 1</option>
                            <option value="kal2">Kalimantan 2</option>
                            <option value="sul1">Sulawesi 1</option>
                            <option value="sul2">Sulawesi 2</option>
                            <option value="mdp">Maluku dan Papua</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="namaSurat">Judul</label>
                        <input type="text" class="form-control @error('namaSurat') is-invalid @enderror" name="namaSurat"
                            value="{{ $outgoingmail->title }}">
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
                            autocomplete="off" value="{{ $outgoingmail->created_at->toDateString() }}" />
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
                                name="nomorSurat" id="nomorSurat" value="{{ $outgoingmail->number }}" readonly>
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
                        <label for="fileSuratKeluar">Upload Surat Keluar</label>
                        <input type="file" class="form-control-file @error('fileSuratKeluar') is-invalid @enderror"
                            id="fileSuratKeluar" name="fileSuratKeluar" value="{{ $outgoingmail->file_path }}">
                        @error('fileSuratKeluar')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <input type="hidden" name="id" value="{{ $outgoingmail->id }}">
                    <div class="form-group mt-5">
                        <input type="submit" class="btn btn-info" value="Ubah">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
