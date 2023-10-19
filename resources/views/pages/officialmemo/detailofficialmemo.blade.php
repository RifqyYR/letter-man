@extends('layouts.main')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h5 mb-0 text-body font-weight-bold">{{ $officialMemo->title }}</h1>
        </div>
        <div class="row mb-4">
            <div class="col-md-7">
                <div class="embed-responsive embed-responsive-4by3">
                    <iframe class="embed-responsive-item"
                        src="{{ asset('/laraview/#../storage/' . $officialMemo->file_path) }}"></iframe>
                </div>
            </div>

        </div>
    </div>
@endsection
