@extends('layouts.template')

@section('content')
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="{{ $user->profile }}" alt="Profile Picture" class="rounded-circle img-fluid"
                            style="width: 150px;">
                        <h5 class="my-3">{{ $user->nama }}</h5>
                        <p class="text-muted mb-1">{{ $user->level->level_nama }}</p>
                        <div class="d-flex justify-content-center mb-2">
                            <form action="{{ url('/profil/upload/' . Auth::user()->user_id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex flex-column align-items-center gap-2">
                                    <div class="input-group justify-content-center">
                                        <input type="file" class="form-control form-control-sm d-none" name="profile"
                                            accept="image/*" id="profileInput">
                                        <label for="profileInput" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-camera"></i> Choose Photo
                                        </label>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-upload"></i> Update Profile
                                    </button>
                                    <small class="text-muted">Allowed formats: JPG, PNG, GIF (Max 2MB)</small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Username</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->username }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->nama }}</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Role</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">{{ $user->level->level_nama }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection