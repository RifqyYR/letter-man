@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row mb-4">
            <div class="card">
                <div class="card-body">
                    <p class="h5 font-weight-bold">
                        {{ $documentAuthorizationLetter->title }}
                    </p>
                    <hr class="my-2 border-black">
                    <div class="row">
                        <div class="col-6">
                            <p><b>Nomor Kebenaran Dokumen</b>
                                <br>
                                {{ $documentAuthorizationLetter->number }}
                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Nomor Nota Dinas Pembayaran</b>
                                <br>
                                {{ $documentAuthorizationLetter->payment_number }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><b>Nomor PAA</b>
                                <br>
                                {{ $documentAuthorizationLetter->contract_number }}
                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Total Pembayaran</b>
                                <br>
                                Rp. {{ $documentAuthorizationLetter->payment_total }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><b>Nama Vendor</b>
                                <br>
                                {{ $documentAuthorizationLetter->vendor_name }}
                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Nomor Rekening Vendor</b>
                                <br>
                                {{ $documentAuthorizationLetter->account_number }}
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p><b>Tanggal Dibuat</b>
                                <br>
                                @php
                                    $created_at = explode(' ', $documentAuthorizationLetter->created_at);
                                    $created_at = $created_at[0];
                                @endphp
                                {{ $created_at }}
                            </p>
                        </div>
                        <div class="col-6">
                            <p><b>Dibuat Oleh</b>
                                <br>
                                {{ $documentAuthorizationLetter->created_by }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
