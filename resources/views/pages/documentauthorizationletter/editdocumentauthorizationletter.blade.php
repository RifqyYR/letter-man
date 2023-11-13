@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        {{-- Header --}}
        <div class="row">
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <h1 class="h3 mb-0 text-gray-800">Ubah Kebenaran Dokumen</h1>
            </div>
        </div>

        {{-- Input Form --}}
        <div class="card mt-2">
            <div class="card-body">
                <form action="/proses-ubah-kebenaran-dokumen" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="tujuan">Tujuan Surat</label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioTemplate" value="PJM"
                                id="radioTemplate1">
                            <label class="form-check-label" for="radioTemplate1">
                                PJM
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="radioTemplate" value="HO"
                                id="radioTemplate2">
                            <label class="form-check-label" for="radioTemplate2">
                                Head Office
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="namaSurat">Nama Pekerjaan</label>
                        <input type="text" class="form-control @error('namaSurat') is-invalid @enderror" name="namaSurat"
                            value="{{ $documentAuthorizationLetter->title }}">
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
                            autocomplete="off" value="{{ $documentAuthorizationLetter->created_at->toDateString() }}" />
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
                                name="nomorSurat" id="nomorSurat" value="{{ $documentAuthorizationLetter->number }}"
                                readonly>
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
                        <label for="nomorKontrak">Nomor Kontrak</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('nomorKontrak') is-invalid @enderror"
                                name="nomorKontrak" id="nomorKontrak"
                                value="{{ $documentAuthorizationLetter->contract_number }}">
                        </div>
                        @error('nomorKontrak')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="namaVendor">Nama Vendor</label>
                        <select class="form-control @error('namaVendor') is-invalid @enderror" name="namaVendor"
                            value="{{ $vendor == null ? $documentAuthorizationLetter->vendor_name . ' - ' . $documentAuthorizationLetter->bank_name . ' - ' . $documentAuthorizationLetter->account_number : $vendor->name . ' - ' . $vendor->bank_name . ' - ' . $vendor->account_number }}"
                            id="editable-select">
                            @foreach ($vendors as $item)
                                <option value="{{ $item->id }}">{{ $item->name . ' - ' . $item->bank_name . ' - ' . $item->account_number }}</option>
                            @endforeach
                        </select>
                        @error('namaVendor')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="jumlahPembayaran">Jumlah Pembayaran</label>
                        <div class="input-group">
                            <div class="input-group-append">
                                <span class="input-group-text">Rp</span>
                            </div>
                            <input type="text" class="form-control @error('jumlahPembayaran') is-invalid @enderror"
                                name="jumlahPembayaran" id="jumlahPembayaran"
                                value="{{ $documentAuthorizationLetter->payment_total }}" onkeyup="moneyFormat(this)">
                        </div>
                        @error('jumlahPembayaran')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="bankPenerima">Bank Penerima</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('bankPenerima') is-invalid @enderror"
                                name="bankPenerima" id="bankPenerima" value="{{ $vendor == null ? $documentAuthorizationLetter->bank_name : $vendor->bank_name }}">
                        </div>
                        @error('bankPenerima')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="nomorRekening">Nomor Rekening</label>
                        <div class="input-group">
                            <input type="text" class="form-control @error('nomorRekening') is-invalid @enderror"
                                name="nomorRekening" id="nomorRekening" value="{{ $vendor == null ? $documentAuthorizationLetter->account_number : $vendor->account_number }}">
                        </div>
                        @error('nomorRekening')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="fileLampiran">Upload Lampiran</label>
                        <input type="file" class="form-control-file @error('fileLampiran') is-invalid @enderror"
                            id="fileLampiran" name="fileLampiran[]" multiple>
                        @error('fileLampiran')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <input type="text" hidden name="id" value="{{ $documentAuthorizationLetter->id }}">
                    <div class="form-group mt-5">
                        <input type="submit" class="btn btn-info" value="Ubah">
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
