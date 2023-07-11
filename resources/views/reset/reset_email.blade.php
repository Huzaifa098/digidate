@extends('layouts.app')

@section('title', 'Email wijzigen')

@section('content')

<main class="login-form">
  <div class="cotainer">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                @if(session()->has('error'))
                    <div class="m-0 text-center alert alert-danger fade show" role="alert">
                        {{ session()->get('error') }}
                    </div>
                @endif
                  <div class="card-header">Email wijzigen</div>
                  <div class="card-body">
                      <form action="{{ route('reset.email.post') }}" method="POST">
                          @csrf
                          <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">Nieuwe email</label>
                            <div class="col-md-6">
                                <input type="text" id="email" class="form-control" name="email" required autofocus>
                                @if ($errors->has('email'))
                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                @endif
                            </div>
                        </div>

                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">Wachtwoord</label>
                              <div class="col-md-6">
                                  <input type="password" id="password" class="form-control" name="password" required autofocus>
                                  @if ($errors->has('password'))
                                      <span class="text-danger">{{ $errors->first('password') }}</span>
                                  @endif
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Wachtwoord Herhalen</label>
                              <div class="col-md-6">
                                  <input type="password" id="password-confirm" class="form-control" name="password_confirmation" required autofocus>
                                  @if ($errors->has('password_confirmation'))
                                      <span class="text-danger">{{ $errors->first('password_confirmation') }}</span>
                                  @endif
                              </div>
                          </div>

                          <div class="col-md-6 offset-md-4 p-2">
                              <button type="submit" class="btn btn-primary">
                                Email wijzigen
                              </button>
                          </div>
                      </form>

                  </div>
              </div>
          </div>
      </div>
  </div>
</main>
@endsection
