@extends('layouts.app')

@section('title', 'Register')


@section('content')

<div class="container w-50 mx-auto bg-light home-page p-0 rounded">
    <form action="{{route('register.store')}}" class="p-5" method="POST">
        @csrf
        <div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="form-group">
                <label for="name">Naam</label>
                <div class="d-flex flex-lg-row flex-sm-column">
                    <input type="name" class="form-control m-1" id="name" name="first_name" placeholder="voornaam" value={{old('first_name')}}>
                    <input type="name" class="form-control m-1" id="name" name="middle_name" placeholder="tussenvoegsel" value={{old('middle_name')}}>
                    <input type="name" class="form-control m-1" id="name" name="last_name" placeholder="achternaam" value={{old('last_name')}}>
                </div>
            </div>
            <br />
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value={{old('email')}}>
            </div>
            <br />
            <div class="form-group">
                <label for="password">Wachtwoord</label>
                <input type="password" class="form-control" id="password" name="password">
            </div>
            <br />
            <div class="form-group">
                <label for="password_confirmation">Wachtwoord herhalen</label>
                <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
            </div>

            <br />
            <div class="form-group">
                <label for="city">Woonplaats</label>
                <select class="form-control" id="city" name="city">
                    <option value="" selected disabled>Selecteer...</option>
                    <option value="amsterdam" @if (old('city') == "amsterdam") {{ 'selected' }} @endif>Amsterdam</option>
                    <option value="rotterdam" @if (old('city') == "rotterdam") {{ 'selected' }} @endif>Rotterdam</option>
                    <option value="den Haag"  @if (old('city') == "den Haag") {{ 'selected' }} @endif>Den Haag</option>
                    <option value="utrecht"   @if (old('city') == "utrecht") {{ 'selected' }} @endif>Utrecht</option>
                    <option value="eindhoven" @if (old('city') == "eindhoven") {{ 'selected' }} @endif>Eindhoven</option>
                    <option value="groningen" @if (old('city') == "groningen") {{ 'selected' }} @endif>Groningen</option>
                    <option value="tilburg"   @if (old('city') == "tilburg") {{ 'selected' }} @endif>Tilburg</option>
                </select>
            </div>

            <br />
            <div class="form-group">
                <label for="phone">Telefoonnummer</label>
                <input type="phone" class="form-control" id="phone" name="phone" value={{old('phone')}}>
            </div>

            <br />
            <div class="form-group">
                <label for="gender">Geslacht</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="" @if (old('gender') !== "man" && old('gender') !== "women") {{ 'selected' }} @endif disabled>Selecteer je geslacht...</option>
                    <option value="man" @if (old('gender') == "man") {{ 'selected' }} @endif>Man</option>
                    <option value="women" @if (old('gender') == "women") {{ 'selected' }} @endif>Vrouw</option>
                </select>
            </div>

            <br />
            <div class="form-group">
                <label for="birthday">Geboortedatum:</label>
                <input type="date" class="form-control" name="birthday" id="birthday"/ value={{old('birthday')}}>
            </div>

            <button class="btn btn-dark mt-5 w-100" type="submit">Account maken</button>
            <br /><br />
            <small>Al een account? <a href="{{route('home')}}" class="text-dark">Inloggen</a></small>
        </div>
      </form>

</div>

@endsection
