@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="row">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Tambah Arsip</h1>
            </div>
        </div>

        {{-- Input Form --}}
        <div class="card mt-2">
            <div class="card-body">
                <form action="/proses-tambah-arsip" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="unitKerja">Unit Kerja</label><br>
                        <select class="form-select" aria-label="Default select example" name="unitKerja" id="unitKerja">
                            <option value="wil4" {{ Auth::user()->work_unit == 'WIL4' ? 'selected' : '' }}>Wilayah 4</option>
                            <option value="kal1" {{ Auth::user()->work_unit == 'KAL1' ? 'selected' : '' }}>Kalimantan 1</option>
                            <option value="kal2" {{ Auth::user()->work_unit == 'KAL2' ? 'selected' : '' }}>Kalimantan 2</option>
                            <option value="sul1" {{ Auth::user()->work_unit == 'SUL1' ? 'selected' : '' }}>Sulawesi 1</option>
                            <option value="sul2" {{ Auth::user()->work_unit == 'SUL2' ? 'selected' : '' }}>Sulawesi 2</option>
                            <option value="mapa" {{ Auth::user()->work_unit == 'MAPA' ? 'selected' : '' }}>Maluku dan Papua</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="namaSurat">Judul</label>
                        <input type="text" class="form-control @error('namaSurat') is-invalid @enderror" name="namaSurat"
                            value="{{ old('namaSurat') }}">
                        @error('namaSurat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="tanggalPembuatan">Tanggal Pembuatan</label>
                        <input type="date" id="tanggalPembuatan" required
                            class="form-control @error('tanggalPembuatan') is-invalid @enderror" name="tanggalPembuatan"
                            autocomplete="off" value="{{ now()->toDateString() }}" />
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
                                name="nomorSurat" id="nomorSurat">
                        </div>
                        @error('nomorSurat')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fileArsip">Upload Arsip</label>
                        <input type="file" class="form-control-file @error('fileArsip') is-invalid @enderror"
                            id="fileArsip" name="fileArsip" accept=".pdf">
                        @error('fileArsip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mt-5">
                        <input type="submit" class="btn btn-info" value="Tambah">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
