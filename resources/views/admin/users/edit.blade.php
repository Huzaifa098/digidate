
@extends('layouts.dashboard')

@section('title', 'Gebruikers: Bewerken')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">Update gebruiker account</div>
                <div class="card-body">
                    <form action="{{ route('users.update', $userr->id ) }}" method="POST">
                        @csrf
                        @method('put')
                        <div class="form-group">
                            <label for="name">Naam:</label>
                            <div class="d-flex justify-content-around">
                                <input type="name" class="form-control m-1" id="name" name="first_name" value="{{ $userr->user_info['first_name'] }}">
                                <span class="text-danger">{{ $errors->first('first_name') }}</span>
                                <input type="name" class="form-control m-1" id="name" name="middle_name" value="{{ $userr->user_info['middle_name'] }}">
                                <span class="text-danger">{{ $errors->first('middle_name') }}</span>
                                <input type="name" class="form-control m-1" id="name" name="last_name" value="{{ $userr->user_info['last_name'] }}">
                                <span class="text-danger">{{ $errors->first('last_name') }}</span>
                            </div>
                        </div>
                        <br>
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" class="form-control m-1" id="email" name="email" value="{{ $userr->email }}">
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password" class="m-1">Nieuwe wachtwoord</label>
                            <input id="password" type="password" class="form-control m-1" name="password">
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="m-1">Nieuwe wachtwoord herhalen</label>
                            <input id="password_confirmation" type="password" class="form-control m-1" name="password_confirmation">
                            <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-primary">Updaten</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection


