@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="row mb-4">
            <div class="col-md-8 mb-2">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                        src="{{ asset('/laraview/#../storage/' . $officialMemo->file_path) }}"></iframe>
                </div>
            </div>
            <div class="col-md-4">
                <div class="row">
                    <div class="col">
                        <p class="h5 text-body font-weight-bold">
                            {{ $officialMemo->title }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Nomor Nota Dinas</b>
                            <br>
                            {{ $officialMemo->number }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Dibuat Oleh</b>
                            <br>
                            {{ $officialMemo->created_by }}
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p><b>Tanggal Dibuat</b>
                            <br>
                            @php
                                $created_at = explode(' ', $officialMemo->created_at);
                                $created_at = $created_at[0];
                            @endphp
                            {{ $created_at }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
