@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Edit User') }}</div>

                    <div class="card-body">
                        <form method="POST" action="/proses-edit-user">
                            @csrf

                            <div class="row mb-3">
                                <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ $user->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="email"
                                    class="col-md-4 col-form-label text-md-end">{{ __('NRP / NIPP') }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="text"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ $user->email }}" required autocomplete="email">

                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="role"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Admin') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" name="role">
                                        <option {{ $user->role == 0 ? 'selected' : '' }} value="0">User Biasa</option>
                                        <option {{ $user->role == 1 ? 'selected' : '' }} value="1">Admin</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <label for="work_unit"
                                    class="col-md-4 col-form-label text-md-end">{{ __('Unit Kerja') }}</label>

                                <div class="col-md-6">
                                    <select class="form-select" name="work_unit">
                                        <option {{ $user->work_unit == 'WIL4' ? 'selected' : '' }} value="WIL4">Wilayah 4</option>
                                        <option {{ $user->work_unit == 'KAL1' ? 'selected' : '' }} value="KAL1">Kalimantan 1</option>
                                        <option {{ $user->work_unit == 'KAL2' ? 'selected' : '' }} value="KAL2">Kalimantan 2</option>
                                        <option {{ $user->work_unit == 'SUL1' ? 'selected' : '' }} value="SUL1">Sulawesi 1</option>
                                        <option {{ $user->work_unit == 'SUL2' ? 'selected' : '' }} value="SUL2">Sulawesi 2</option>
                                        <option {{ $user->work_unit == 'MAPA' ? 'selected' : '' }} value="MAPA">Maluku dan Papua</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Edit
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
