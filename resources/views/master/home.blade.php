@extends('layouts.app')

@section('title', 'Home')

@section('content')

<div class="container w-75 mx-auto bg-light d-xl-flex d-lg-flex justify-content-between home-page p-0 rounded">
    <form action="{{ route('login') }}" class="p-5" method="POST">
        @csrf
         @if(session()->has('message'))
                <div class="m-0 text-center alert alert-danger fade show" role="alert">
                    {{ session()->get('message') }}
                </div>
         @endif
        <div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email">
              </div>
              <br />
              <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" class="form-control" id="password" name="password">
              </div>

              <button class="btn btn-dark mt-5 w-100" type="submit">Inloggen</button>
              <br /><br />
              <small><a href="{{route('forget.password.get')}}" class="text-dark">Wachtwood vergeten</a></small>
              <br /><br />
              <small>Nog geen account? <a href="{{route('register')}}" class="text-dark">Account aanmaken</a></small>
        </div>
      </form>
      <div class="home-image"></div>
</div>

@endsection
