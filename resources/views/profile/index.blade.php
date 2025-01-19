@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-center">
                        <h3>{{ __('Your Profile') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 text-end">
                                <strong>{{ __('Name:') }}</strong>
                            </div>
                            <div class="col-md-8">
                                {{ Auth::user()->name }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 text-end">
                                <strong>{{ __('Email:') }}</strong>
                            </div>
                            <div class="col-md-8">
                                {{ Auth::user()->email }}
                            </div>
                        </div>

                        @if (Auth::user()->contactDetails)
                            <div class="row mb-3">
                                <div class="col-md-4 text-end">
                                    <strong>{{ __('Phone:') }}</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Auth::user()->contactDetails->phone }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-end">
                                    <strong>{{ __('Address:') }}</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Auth::user()->contactDetails->address_line_1 }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-end">
                                    <strong>{{ __('City:') }}</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Auth::user()->contactDetails->city }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-end">
                                    <strong>{{ __('Postal Code:') }}</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Auth::user()->contactDetails->postal_code }}
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-4 text-end">
                                    <strong>{{ __('Country:') }}</strong>
                                </div>
                                <div class="col-md-8">
                                    {{ Auth::user()->contactDetails->country }}
                                </div>
                            </div>
                        @else
                            <div class="alert alert-warning text-center">
                                {{ __('No contact details available.') }}
                            </div>
                        @endif

                        <div class="text-center mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">{{ __('Edit Profile') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
