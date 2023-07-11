@extends('layouts.app')

@section('title', 'Register Voltooien')

@section('content')

<div class="container w-50 mx-auto bg-light p-0 rounded">
    @if(session()->has('message'))
            <div class="m-0 text-center alert alert-warning fade show" role="alert">
                {{ session()->get('message') }}
            </div>
    @endif

    @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
    @endif

    <form action="{{ route('register.finish') }}" class="p-5" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <div class="form-group">
                <label for="image">Profile foto</label>
                <input type="file" class="form-control" id="image" name="image">
            </div>
            <br />
            <div class="form-group">
                <label for="bio">Bio</label>
                <textarea class="form-control" id="bio" name="bio" rows="10" cols="30" placeholder="Beschrijf je zelf..."></textarea>
                <small class="d-flex flex-row-reverse">Max 255</small>
            </div>
            <br />
            <div class="form-group">
                <label for="gender">Voorkeur geslacht</label>
                <select class="form-control" id="gender" name="gender">
                    <option value="" @if (old('gender') !== "man" && old('gender') !== "women") {{ 'selected' }} @endif disabled>Selecteer...</option>
                    <option value="man" @if (old('gender') == "man") {{ 'selected' }} @endif>Man</option>
                    <option value="women" @if (old('gender') == "women") {{ 'selected' }} @endif>Vrouw</option>
                </select>
            </div>
            <br />
            <div class="form-group">
                <label for="city">Voorkeur woonplaats</label>
                <select class="form-control" id="city" name="city">
                    <option value="" selected disabled>Selecteer...</option>
                    <option value="amsterdam">Amsterdam</option>
                    <option value="rotterdam">Rotterdam</option>
                    <option value="den Haag">Den Haag</option>
                    <option value="utrecht">Utrecht</option>
                    <option value="eindhoven">Eindhoven</option>
                    <option value="groningen">Groningen</option>
                    <option value="tilburg">Tilburg</option>
                </select>
            </div>
            <br />

            @if (count($tags))
            <div class="form-group">
                <label><b>Voorkeur tags:</b></label>
                <br>
                <div class="d-flex justify-content-around">
                    @foreach ($tags as $tag)
                    <div>
                        <label for="{{$tag->id}}">{{ $tag->name }}</label>
                        <input class="m-0" type="checkbox" id="{{$tag->id}}" name="tags[]" value="{{ $tag->id }}">
                    </div>
                @endforeach
                </div>
            </div>
            <br>
            @endif
            <div class="d-flex flex-lg-column flex-sm-column mx-auto">
                <label><b>Voorkeur leeftijd:</b></label>
                <div class="form-group d-flex justify-content-between m-1">
                    <label for="min" class="form-label">Minimaal:</label>
                    <input type="range" class="form-range w-50" min="18" max="60" id="min" name="age_min" oninput="this.nextElementSibling.value = this.value" value="18">
                    <output class="m-0 p-0"></output>
                </div>

                <div class="form-group d-flex justify-content-between m-1">
                    <label for="max" class="form-label">Maximaal:</label>
                    <input type="range" class="form-range w-50" min="18" max="60" id="max" name="age_max" oninput="this.nextElementSibling.value = this.value" value="60">
                    <output class="m-0 p-0"></output>
                </div>
            </div>
            <br />



        <div class="d-flex justify-content-around w-75 mx-auto">
            <a class="m-2" href="{{ route('home') }}">Overslaan</a>
            <button class="btn btn-dark w-100 p-2" type="submit">Doorgaan</button>
        </div>

        </div>

    </form>
</div>
@endsection
