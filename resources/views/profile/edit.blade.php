@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>{{ __('Edit Profile') }}</h3>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', Auth::user()->name) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">{{ __('Phone') }}</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', Auth::user()->contactDetails->phone ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="address_line_1" class="form-label">{{ __('Address') }}</label>
                                <input type="text" class="form-control" id="address_line_1" name="address_line_1" value="{{ old('address_line_1', Auth::user()->contactDetails->address_line_1 ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="city" class="form-label">{{ __('City') }}</label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city', Auth::user()->contactDetails->city ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="postal_code" class="form-label">{{ __('Postal Code') }}</label>
                                <input type="text" class="form-control" id="postal_code" name="postal_code" value="{{ old('postal_code', Auth::user()->contactDetails->postal_code ?? '') }}">
                            </div>

                            <div class="mb-3">
                                <label for="country" class="form-label">{{ __('Country') }}</label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ old('country', Auth::user()->contactDetails->country ?? '') }}">
                            </div>

                            <button type="submit" class="btn btn-primary">{{ __('Update Profile') }}</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
